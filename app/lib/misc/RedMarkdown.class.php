<?php

require(AgaviConfig::get('core.lib_dir') . '/vendor/markdown.php');

# Define the indent of a header ids.
@define( 'MARKDOWN_INDENT_HEADERS',     0);

class RedMarkdown extends MarkdownExtra_Parser
{

	var $indent_headers = MARKDOWN_INDENT_HEADERS;

	var $code_callback = null;

	function transform($text, $indent_headers = null)
	{
		if ($indent_headers === null)
		{
			$indent_headers = MARKDOWN_INDENT_HEADERS;
		}
		$this->indent_headers = $indent_headers;

		return parent::transform($text);
	}

	function setCodeCallback($callback)
	{
		$this->code_callback = $callback;
	}


	function doCodeBlocks($text)
	{
		$text = preg_replace_callback('{
				(?:\n\n|\A\n?)
				(?:[ ]{'.$this->tab_width.'}\{\.([-_:a-zA-Z0-9]+)\}\n)?
				(	            # $1 = the code block -- one or more lines, starting with a space/tab
				  (?>
					[ ]{'.$this->tab_width.'}  # Lines must start with a tab or a tab-width of spaces
					.*\n+
				  )+
				)
				((?=^[ ]{0,'.$this->tab_width.'}\S)|\Z)	# Lookahead for non-space at line-start, or end of doc
			}xm',
			array(&$this, '_doCodeBlocks_callback'), $text);

		return $text;
	}

	function _doCodeBlocks_callback($matches)
	{

		$attr = '';
		if ($class = $matches[1]) {
			$attr = ' class="' . $class . '"';
		}

		$codeblock = $matches[2];

		$codeblock = $this->outdent($codeblock);
		$codeblock = htmlspecialchars($codeblock, ENT_NOQUOTES);

		# trim leading newlines and trailing newlines
		$codeblock = preg_replace('/\A\n+|\n+\z/', '', $codeblock);

		if ($this->code_callback)
		{
			$codeblock = call_user_func($this->code_callback, $codeblock, $class);
		}
		else
		{
			$codeblock = "<pre$attr><code>$codeblock\n</code></pre>";
		}

		return "\n\n".$this->hashBlock($codeblock)."\n\n";
	}

	function _doHeaders_callback_setext($matches)
	{
		if ($matches[3] == '-' && preg_match('{^- }', $matches[1]))
			return $matches[0];
		$level = ($matches[3]{0} == '=' ? 1 : 2) + $this->indent_headers;
		$attr  = $this->_doHeaders_attr($id =& $matches[2]);
		$block = "<h$level$attr>".$this->runSpanGamut($matches[1])."</h$level>";
		return "\n" . $this->hashBlock($block) . "\n\n";
	}

	function _doHeaders_callback_atx($matches)
	{
		$level =  intval(strlen($matches[1])) + $this->indent_headers;
		$attr  = $this->_doHeaders_attr($id =& $matches[3]);
		$block = "<h$level$attr>".$this->runSpanGamut($matches[2])."</h$level>";
		return "\n" . $this->hashBlock($block) . "\n\n";
	}


}

?>