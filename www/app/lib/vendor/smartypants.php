<?php

/*  SmartyPants-PHP 1.5.1-r1
 *  <http://monauraljerk.org/smartypants-php/>
 *  A PHP4 port of John Gruber's SmartyPants plug-in for 
 *  Movable Type, BBEdit, Blosxom.
 * 
 *  SmartyPants is Copyright (c) 2003 John Gruber 
 *  <http://daringfireball.net>
 *
 *  SmartyPants-PHP ported February 2004 by matthew mcglynn 
 *  <http://debris.com>
 *
 *  SmartyPants 1.5.1 changes rolled in May 2004 by Alex Rosenberg
 *
 *  SmartyPants-PHP provides a function-based API to the 
 *  punctuation translation routines of SmartyPants 1.5.1. 
 *  Code specific to Movable Type, BBEdit, and Blosxom has 
 *  been removed as it is not useful in a PHP context.
 *
 *  SmartyPants official home URL: 
 *  http://daringfireball.net/projects/smartypants/
 *
 *  SmartyPants-PHP official home URL:
 *  http://monauraljerk.org/smartypants-php/
 *
 *  Comments from the original PERL code are retained below, marked with '#'
 *  Comments specific to SmartyPants-PHP are marked '//'
 *
 */

# SmartyPants  -  A Plug-In for Movable Type, Blosxom, and BBEdit
# by John Gruber
# http://daringfireball.net
#
# See the readme or POD for details, installation instructions, and
# license information.
#
# Copyright (c) 2003-2004 John Gruber
#

define('SMARTYPANTS_PHP_VERSION', '1.5.1php-r1');

// how many times do you want to allow the tokenizer to loop?
// The higher the value, the longer your system could churn
// given an infinite-loop bug (or really really really long text string).
define('MAX_TOKENIZER_LOOPS', 2000);

// print error on tokenizer loop problem? 
define('ADVISE_TOKENIZER_FAILURE', FALSE);

// Control the operation of the SmartyPants() routine by passing
// any of the following command modes. To combine modes, 'or' them
// together.
define('DO_QUOTES', 1);
define('DO_BACKTICKS_DOUBLE', 2);
define('DO_BACKTICKS_ALL', 4);
define('DO_DASHES', 8);
define('DO_OLDSCHOOL_DASHES', 16);
define('DO_INVERTED_OLDSCHOOL_DASHES', 32);
define('DO_ELLIPSES', 64);
define('DO_QUOT_CONV', 128);
define('DO_STUPEFY', 256);

// Set default operational mode
define('DEFAULT_OPERATION_MODE', DO_QUOTES | DO_BACKTICKS_DOUBLE | DO_DASHES | DO_ELLIPSES);

// keys for $tokens hash
define('TOKENS_TYPE_TEXT', 'text');
define('TOKENS_TYPE_TAG',  'tag');

// punctuation inside these tags will not be modified
define('TAGS_TO_SKIP', "{<(/?)(?:pre|code|kbd|script|math)[\s>]}i");

// string SmartyPants ( string input [, int mode] )
// This function provides the main API to the library. In most cases, you'll
// simply pass an HTML string to this function. The function will return a
// version of your input string, with punctuation made 'smart.'
function SmartyPants($text = '', $mode = DEFAULT_OPERATION_MODE) {

    // quick return for empty string
    if ($text == '') {
        return '';
    }
    
    // default all commands to FALSE, then reset according to $mode
    $do_quotes = $do_backticks_double = $do_backticks_all = FALSE;
    $do_dashes = $do_oldschool_dashes = $do_inverted_oldschool_dashes = FALSE;
    $do_ellipses = $do_stupefy = $convert_quot = FALSE; 

    // setting these flags introduces a layer of abstraction that turned out
    // to be unnecessary. Such is porting.
    if ($mode & DO_QUOTES)                      { $do_quotes    = TRUE;           }
    if ($mode & DO_BACKTICKS_DOUBLE)            { $do_backticks_double = TRUE;    }
    if ($mode & DO_BACKTICKS_ALL)               { $do_backticks_all = TRUE;       }
    if ($mode & DO_DASHES)                      { $do_dashes    = TRUE;           }
    if ($mode & DO_OLDSCHOOL_DASHES)            { $do_oldschool_dashes = TRUE;    }
    if ($mode & DO_INVERTED_OLDSCHOOL_DASHES)   { $do_inverted_oldschool_dashes = TRUE; }
    if ($mode & DO_ELLIPSES)                    { $do_ellipses  = TRUE;           }
    if ($mode & DO_QUOT_CONV)                   { $convert_quot = TRUE;           }
    if ($mode & DO_STUPEFY)                     { $do_stupefy   = TRUE;           }
    
    // tokenize input string -- break it into HTML tags and the text between them.
    $tokens = array();
    _tokenize($text, $tokens);

    $result = '';
    $in_pre = FALSE;  # Keep track of when we're inside <pre> or <code> tags.

    $prev_token_last_char = '';  # This is a cheat, used to get some context
                                 # for one-character tokens that consist of 
                                 # just a quote char. What we do is remember
                                 # the last character of the previous text
                                 # token, to use as context to curl single-
                                 # character quote tokens correctly.

    foreach  ($tokens as $data) {
        if ($data['type'] == TOKENS_TYPE_TAG) {
            # Don't mess with quotes inside tags.
            $result .= $data['body'];

            // if the current tag contains text that should not be
            // modified, set $in_pre to TRUE
            if (preg_match(TAGS_TO_SKIP, $data['body'], $hits)) {
                $in_pre = ($hits[1] == '') ? TRUE : FALSE;
            }
        } else {
            $t = $data['body'];
            $last_char = substr($t, -1); # Remember last char of this token before processing.
            if (! $in_pre) {

                $t = ProcessEscapes($t);

                if ($convert_quot) {
                    $t = str_replace('&quot;', '"', $t);
                }

                if ($do_dashes) {
                    $t = EducateDashes($t);
                }
                elseif ($do_oldschool_dashes) {
                    $t = EducateDashesOldSchool($t);
                }
                elseif ($do_inverted_oldschool_dashes) {
                    $t = EducateDashesOldSchoolInverted($t);
                }
                
                if ($do_ellipses) {
                    $t = EducateEllipses($t);
                }

                # Note: backticks need to be processed before quotes.
                if ($do_backticks_double || $do_backticks_all) {
                    $t = EducateBackticks($t);
                }
                if ($do_backticks_all) {
                    $t = EducateSingleBackticks($t);
                }

                if ($do_quotes) {
                    if ($t == "'") {
                        # Special case: single-character ' token
                        if (preg_match("/\S/", $prev_token_last_char)) {
                            $t = "&#8217;";
                        }
                        else {
                            $t = "&#8216;";
                        }
                    }
                    elseif ($t == '"') {
                        # Special case: single-character " token
                        if (preg_match("/\S/", $prev_token_last_char)) {
                            $t = "&#8221;";
                        }
                        else {
                            $t = "&#8220;";
                        }
                    }
                    else {
                        $t = EducateQuotes($t);
                    }
                }

                if ($do_stupefy) {
                    $t = StupefyEntities($t);
                }
            } 

            $prev_token_last_char = $last_char;
            $result .= $t;
        }
    }

    return $result;
}



function EducateQuotes($s = '') {
#
#   Parameter:  String.
#
#   Returns:    The string, with "educated" curly quote HTML entities.
#
#   Example input:  "Isn't this fun?"
#   Example output: &#8220;Isn&#8217;t this fun?&#8221;

    # Make our own "punctuation" character class, because the POSIX-style
    # [:PUNCT:] is only available in Perl 5.6 or later:

    // Original PERL:
    // my $punct_class = qr/[!"#\$\%'()*+,-.\/:;<=>?\@\[\\\]\^_`{|}~]/;
    // For some reason, \[\\\] fails in PHP; must be \[\]\\

    // quick return for empty string
    if ($s == '') {
        return '';
    }

    $punct_class = <<<REGEX
!"#\$\%'()*+,-.\/:;<=>?\@\[\]\\\^_`{|}~
REGEX;

    # Special case if the very first character is a quote
    # followed by punctuation at a non-word-break. Close the quotes by brute force:
    $s = preg_replace("/^'(?=[$punct_class]\B)/", '&#8217;', $s);
    $s = preg_replace("/^\"(?=[${punct_class}]\B)/", '&#8221;', $s);

    # Special case for double sets of quotes, e.g.:
    #   <p>He said, "'Quoted' words in a larger quote."</p>
    $s = preg_replace("/\"'(?=\w)/", '&#8220;&#8216;', $s);
    $s = preg_replace("/'\"(?=\w)/", '&#8216;&#8220;', $s);

    # Special case for decade abbreviations (the '80s):
    $s = preg_replace("/'(?=\d{2}s)/", '&#8217;', $s);

    $close_class = "[^ \t\r\n\[\{\(\-]";
    $dec_dashes = "&\#8211;|&\#8212;";

    # Get most opening single quotes:
    $pattern = <<<REGEX
{
    (
        \s          |   # a whitespace char, or
        &nbsp;      |   # a non-breaking space entity, or
        --          |   # dashes, or
        &[mn]dash;  |   # named dash entities, or
        $dec_dashes |   # or decimal entities
        &\#x201[34];    # or hex
    )
   '                   # the quote
   (?=\w)              # followed by a word character
}x
REGEX;
    $s = preg_replace($pattern, "$1&#8216;", $s);
    
    # Single closing quotes:
    $pattern = <<<REGEX
{
    ($close_class)?
    '
    (?(1)|          # If $1 captured, then do nothing;
      (?=\s | s\b)  # otherwise, positive lookahead for a whitespace
    )               # char or an 's' at a word ending position. This
                    # is a special case to handle something like:
                    # "<i>Custer</i>'s Last Stand."
}xi
REGEX;
    $s = preg_replace($pattern, "$1&#8217;", $s);

    # Any remaining single quotes should be opening ones:
    $s = str_replace("'", '&#8216;', $s);

    # Get most opening double quotes:
    $pattern = <<<REGEX
{
    (
        \s          |   # a whitespace char, or
        &nbsp;      |   # a non-breaking space entity, or
        --          |   # dashes, or
        &[mn]dash;  |   # named dash entities, or
        $dec_dashes |   # or decimal entities
        &\#x201[34];    # or hex
    )
    "                   # the quote
    (?=\w)              # followed by a word character
}x
REGEX;
    $s = preg_replace($pattern, "$1&#8220;", $s);

    # Double closing quotes:
    $pattern = <<<REGEX
{
   ($close_class)?
    "
    (?(1)|(?=\s))   # If $1 captured, then do nothing;
                    # if not, then make sure the next char is whitespace.
}x
REGEX;
    $s = preg_replace($pattern, "$1&#8221;", $s);
    
    # Any remaining quotes should be opening ones.
    $s = str_replace('"', '&#8220;', $s);

    return $s;
}


function EducateBackticks($s = '') {
#
#   Parameter:  String.
#   Returns:    The string, with ``backticks'' -style double quotes
#               translated into HTML curly quote entities.
#
#   Example input:  ``Isn't this fun?''
#   Example output: &#8220;Isn't this fun?&#8221;

    $s = str_replace("``",  '&#8220;', $s);
    $s = str_replace("''",  '&#8221;', $s);
    return $s;
}


function EducateSingleBackticks($s = '') {
#
#   Parameter:  String.
#   Returns:    The string, with `backticks' -style single quotes
#               translated into HTML curly quote entities.
#
#   Example input:  `Isn't this fun?'
#   Example output: &#8216;Isn&#8217;t this fun?&#8217;

    $s = str_replace("`",  '&#8216;', $s);
    $s = str_replace("'",  '&#8217;', $s);
    return $s;
}


function EducateDashes($s = '') {
#
#   Parameter:  String.
#
#   Returns:    The string, with each instance of "--" translated to
#               an em-dash HTML entity.

    $s = str_replace('--',  '&#8212;', $s);
    return $s;
}


function EducateDashesOldSchool($s = '') {
#
#   Parameter:  String.
#
#   Returns:    The string, with each instance of "--" translated to
#               an en-dash HTML entity, and each "---" translated to
#               an em-dash HTML entity.

    $s = str_replace('---', '&#8212;', $s);
    $s = str_replace('--',  '&#8211;', $s);
    return $s;
}


function EducateDashesOldSchoolInverted($s = '') {
#
#   Parameter:  String.
#
#   Returns:    The string, with each instance of "--" translated to
#               an em-dash HTML entity, and each "---" translated to
#               an en-dash HTML entity. Two reasons why: First, unlike the
#               en- and em-dash syntax supported by
#               EducateDashesOldSchool(), it's compatible with existing
#               entries written before SmartyPants 1.1, back when "--" was
#               only used for em-dashes.  Second, em-dashes are more
#               common than en-dashes, and so it sort of makes sense that
#               the shortcut should be shorter to type. (Thanks to Aaron
#               Swartz for the idea.)

    $s = str_replace('---', '&#8211;', $s);
    $s = str_replace('--',  '&#8212;', $s);
    return $s;
}


function EducateEllipses($s = '') {
#
#   Parameter:  String.
#   Returns:    The string, with each instance of "..." translated to
#               an ellipsis HTML entity. Also converts the case where
#               there are spaces between the dots.
#
#   Example input:  Huh...?
#   Example output: Huh&#8230;?

    $s = str_replace('...', '&#8230;', $s);
    $s = str_replace('. . .', '&#8230;', $s);
    return $s;
}


function StupefyEntities($s = '') {
#
#   Parameter:  String.
#   Returns:    The string, with each SmartyPants HTML entity translated to
#               its ASCII counterpart.
#
#   Example input:  &#8220;Hello &#8212; world.&#8221;
#   Example output: "Hello -- world."
#

    $inputs = array(
        '&#8211;',    // en-dash
        '&#8212;',    // em-dash
        '&#8216;',    // open single quote
        '&#8217;',    // close single quote
        '&#8220;',    // open double quote
        '&#8221;',    // close double quote
        '&#8230;',    // ellipsis
    );
    $outputs = array(
        '-',
        '--',
        "'",
        "'",
        '"',
        '"',
        '...',
    );

    $s = str_replace($inputs, $outputs, $s);
    return $s;
}


function SmartyPantsVersion() {
    return SMARTYPANTS_PHP_VERSION;
}


function ProcessEscapes($s='') {
#
#   Parameter:  String.
#   Returns:    The string, after processing the following backslash
#               escape sequences. This is useful if you want to force a "dumb"
#               quote or other character to appear.
#
#               Escape  Value
#               ------  -----
#               \\      &#92;
#               \"      &#34;
#               \'      &#39;
#               \.      &#46;
#               \-      &#45;
#               \`      &#96;

    // Regular expressions are unnecessary here; a simple string
    // pattern with str_replace() will suffice. 

    $inputs = array(
        '\\\\',
        '\"',
        "\'",
        '\.',
        '\-',
        '\`',
    );
    
    $outputs = array(
        '&#92;',
        '&#34;',
        '&#39;',
        '&#46;',
        '&#45;',
        '&#96;',
    );
    
    $s = str_replace($inputs, $outputs, $s);
    return $s;
}


function _tokenize(&$str, &$tokens) {
#
#   Parameter:  Pointer to string containing HTML markup,
#               pointer to array to store results.
#
#               Output array contains tokens comprising the input
#               string. Each token is either a tag (possibly with nested,
#               tags contained therein, such as <a href="<MTFoo>">, or a
#               run of text between tags. Each element of the array is a
#               two-element array; the first is either 'tag' or 'text';
#               the second is the actual value.
#
#   Based on the _tokenize() subroutine from Brad Choate's MTRegex plugin.
#       <http://www.bradchoate.com/past/mtregex.php>


    $len = strlen($str);

    $depth = 6;
    $nested_tags = str_repeat('(?:<(?:[^<>]|', $depth);
    $nested_tags = substr($nested_tags, 0, -1);
    $nested_tags .= str_repeat(')*>)', $depth);

    $match = "/(?s: <! ( -- .*? -- \s* )+ > ) |
               (?s: <\? .*? \?> ) |
               $nested_tags/x";

    $last_tag_end = -1;
    $loops = $offset = 0;

//433 PHP 4.3.3 is required for this
//433    while (preg_match($match, $str, $hits, PREG_OFFSET_CAPTURE, $offset)) {
    while (preg_match($match, substr($str, $offset), $hits, PREG_OFFSET_CAPTURE)) {

        $extracted_tag = $hits[0][0];   // contains the full HTML tag
//433   $tag_start = (int)$hits[0][1];  // position of captured in string
        $tag_start = $offset + (int)$hits[0][1];  // position of captured in string
        $offset = $tag_start + 1;       // tells preg_match where to start on next iteration

        // if this tag isn't next to the previous one, store the interstitial text
        if ($tag_start > $last_tag_end) {
            $tokens[] = array('type' => TOKENS_TYPE_TEXT,
                              'body' => substr($str, $last_tag_end+1, $tag_start-$last_tag_end-1));
        }

        $tokens[] = array('type' => TOKENS_TYPE_TAG,
                          'body' => $extracted_tag);

        $last_tag_end = $tag_start + strlen($extracted_tag) - 1;
        
        if ($loops++ > MAX_TOKENIZER_LOOPS) { 
        
            if (ADVISE_TOKENIZER_FAILURE) {
                print "SmartyPants _tokenize failure."; 
            }
            return; 
        }
    }


    // if text remains after the close of the last tag, grab it
    if ($offset < $len) {
        $tokens[] = array('type' => TOKENS_TYPE_TEXT,
                          'body' => substr($str, $last_tag_end + 1));
    }

    return;
    
}

?>