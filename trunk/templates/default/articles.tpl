<html>
<head>
<title>PHP extension for mamanging templates and performing variable interpolation.</title>
</head>
<body>
<!-- INDEX BEGIN -->
<ul>
  <li><a href="#name">name</a>
  <li><a href="#SYNOPSIS">SYNOPSIS</a>
  <li><a href="#DESCRIPTION">DESCRIPTION</a>
  <li><a href="#CORE_METHODS">CORE METHODS</a>
    <ul>
      <li><a href="#define_array_key_value_pairs_">define( array( key,value pairs) )</a>
      <li><a href="#define_nofile_alias_define_ra">define_nofile() alias: define_raw()</a>
      <li><a href="#define_dynamic_Macro_ParentNa">define_dynamic($Macro, $Parentname)</a>
      <li><a href="#clear_dynamic_Macro_">clear_dynamic($Macro)</a>
      <li><a href="#assign_key_value_pair_or_ar">assign( (key,value pair) or ( array(key value pairs) )</a>
      <li><a href="#parse_RETURN_FileHandle_s_">parse(RETURN, FileHandle(s) )</a>
      <li><a href="#strict_">strict()</a>
      <li><a href="#no_strict_">no_strict()</a>
      <li><a href="#FastPrint_HANDLE_">FastPrint(HANDLE)</a>
      <li><a href="#FastWrite_HANDLE_">FastWrite(HANDLE)</a>
    </ul>
  <li><a href="#OTHER_METHODS">OTHER METHODS</a>
    <ul>
      <li><a href="#fetch_HANDLE_">fetch(HANDLE)</a>
      <li><a href="#get_assigned_Var_Christian_Bra">get_assigned($Var) Christian Brandel cbrandel@gmx.de</a>
      <li><a href="#clear_">clear()</a>
      <li><a href="#clear_parse_">clear_parse()</a>
      <li><a href="#clear_href_KEY_">clear_href(KEY)</a>
      <li><a href="#clear_define_">clear_define()</a>
      <li><a href="#clear_tpl_">clear_tpl()</a>
      <li><a href="#clear_all_">clear_all()</a>
      <li><a href="#Variables">Variables</a>
      <li><a href="#Variable_Interpolation_Template">Variable Interpolation (Template Parsing)</a>
      <li><a href="#FULL_EXAMPLE">FULL EXAMPLE</a>
    </ul>
  <li><a href="#VERSION">VERSION</a>
  <li><a href="#AUTHOR">AUTHOR</a>
  <li><a href="#DOCUMENTATION">DOCUMENTATION</a>
  <li><a href="#SEE_ALSO">SEE ALSO</a>
</ul>
<!-- INDEX END -->
<br /><br />
<a class="href" name="name">Nazwa</a>
<span class="line"></span>
<p>
FastTemplate 1.1.5 - PHP extension for managing templates and performing variable interpolation.
</p>

<a class="href" name="SYNOPSIS">FastTemplate - sk³adnia</a>
<span class="line"></span>
<p>
<div class="code">
{CODE_1}
</div>
</p>
<br />
<a class="href" name="DESCRIPTION">FastTemplate - opis</a>
<span class="line"></span>
<p>
<strong>What is a template?</strong>
<p> A template is a text file with variables in it. When a template is parsed, the variables are interpolated to text. (The text can be a few bytes or a few hundred kilobytes.) Here is a simple template with one variable ('{name}'):
<div class="code">
Hello {name}.  How are you?
</div>
<p> <strong>When are templates useful?</strong>
<p> Templates are very useful for CGI programming, because adding HTML to your PHP code clutters your code and forces you to do any HTML modifications. By putting all of your HTML in seperate template files, you can let a graphic or interface designer change the look of your application without having to bug you, or let them muck around in your PHP code.
<p> <strong>Why use FastTemplate?</strong>
<p> <strong>Speed</strong>
<p> FastTemplate parses with a single regular expression. It just does simple variable interpolation (i.e. there is no logic that you can add to templates - you keep the logic in the code). That's why it's has 'Fast' in it's name!
<p> <strong>Flexibility</strong>
<p> The API is robust and flexible, and allows you to build very complex HTML documents/interfaces. It is also completely written in PHP and (should) work on Unix or NT. Also, it isn't restricted to building HTML documents -- it could be used to build any ascii based document (postscript, XML, email - anything).
<p> <strong>What are the steps to use FastTemplate?</strong>
<p> The main steps are:
<p>
<div class="code">
1. define<br />
2. assign<br />
3. parse<br />
4. FastPrint<br />
</div>
<p> These are outlined in detail in CORE METHODS below.
<p>

<a class="href" name="CORE_METHODS">CORE METHODS </a>
<span class="line"></span>
<p>
<div class="code">
<a name="define_array_key_value_pairs_">define(array(key, value pairs))</a>
</div>
<p>
The method <code>define()</code> maps a template filename to a (usually shorter) name;
<p>
<div class="code">
{CODE_2}
</div>
<p> This new name is the name that you will use to refer to the templates. Filenames should not appear in any place other than a <code>define().</code>
<p> (Note: This is a required step! This may seem like an annoying extra step when you are dealing with a trivial example like the one above, but when you are dealing with dozens of templates, it is very handy to refer to templates with names that are indepandant of filenames.)
<p> TIP: Since <code>define()</code> does not actually load the templates, it is faster and more legible to define all the templates with one call to <code>define().</code>
<p>
<p>
<span class="line"></span>
<div class="code">
	<a name="define_nofile_alias_define_ra">define_nofile() alias: define_raw()</a>
</div>	
<strong>THESE METHODS ARE NOT PORTED TO THE PHP VERSION</strong>
<p> And probably never will be. The purpose of this class is to <strong>eliminate</strong> HTML from your PHP code, not to create new ways of adding it back in.
<p>
<p>
<span class="line"></span>
<div class="code">
	<a name="define_dynamic_Macro_ParentNa">define_dynamic($Macro, $Parentname)</a>
</div>
<p>
Nino Martincevic, <a href="MAILTO:don@agi.de,">don@agi.de,</a> emailed me with a question about doing something like this, and I thought it was a such a cool idea I immediately sat down and cranked it out...
<p> You can define dynamic content within a static template. (Lists) Here's an example of <code>define_dynamic();</code>
<p>
<div class="code">
{CODE_3}
</div>
<p> This tells FastTemplate that buried in the ``table'' template is a dynamic block, named ``row''. In older verions of FastTemplate (pre 0.7) this ``row'' template would have been defined as it's own file. Here's how a dynamic block appears within a template file;
<p>
<div class="code">
	&lt;!-- name: dynamic.html --&gt;<br />
	&lt;table&gt;<br /><br />
	
	&lt;!-- BEGIN DYNAMIC BLOCK: row --&gt;<br />
	&lt;tr&gt;<br />
	&lt;td&gt;{NUMBER}&lt;/td&gt;<br />
	&lt;td&gt;{BIG_NUMBER}&lt;/td&gt;<br />
	&lt;/tr&gt;<br />
	&lt;!-- END DYNAMIC BLOCK: row --&gt;<br /><br />

	&lt;/table&gt;<br />
	&lt;!-- END: dynamic.html --&gt;
</div>
<p> The syntax of your BEGIN and END lines needs to be VERY exact. It is case sensitive. The code block begins on a new line all by itself. There cannot be ANY OTHER TEXT on the line with the BEGIN or END statement. (although you can have any amount of whitespace before or after) It must be in the format shown;
<p>
<div class="code">
	&lt;!-- BEGIN DYNAMIC BLOCK: handle_name --&gt;
</div>
<p> The line must be exact, right down to the spacing of the characters. The same is true for your END line. The BEGIN and END lines cannot span multiple lines. Now when you call the <code>parse()</code> method, FastTemplate will automatically spot the dynamic block, strip it out, and use it exactly as if you had defined it as a stand-alone template. No additional work is required on your part to make it work - just define it, and FastTemplate will do the rest. Included with this archive should have been a file named <strong>define_dynamic.phtml</strong> which shows a working example of a dynamic block.
<p> There are a few rules when using dynamic blocks - dynamic blocks should not be nested inside other dynamic blocks - strange things WILL occur. You -can- have more than one nested block of code in a page, but of course, no two blocks can share the same defined handle. The error checking for <code>define_dynamic()</code> is miniscule at best. If you define a dynamic block and FastTemplate fails to find it, no errors will be generated, just really weird output. (FastTemplate will not append the dynamic data to the retured output) Since the BEGIN and END lines are stripped out of the parsed results, if you ever see your BEGIN or END line in the parsed output, that means that FastTemplate failed to find that dynamic block.
<p>
<p>
<span class="line"></span>
<div class="code">
	<a name="clear_dynamic_Macro_">clear_dynamic($Macro) </a>
</div>
<p>
This provides a method to remove the dynamic block definition from the parent macro provided that you haven't already parsed the template. Using our example above:
<p>
<div class="code">
	$tpl-&gt;clear_dynamic("row");
</div>
<p> Would completely strip all of the <strong>unparsed</strong> dynamic blocks named ``row'' from the parent template. This method won't do a thing if the template has already been parsed! (Because the required BEGIN and END lines have been removed through the parsing) This method works well when you are accessing a database, and your ``rows'' may or may not return anything to print to the template. If your database query doesn't return anything, you can now strip out the rows you've set up for the results. (Gee, maybe I ran into this problem myself ? :-)
<p>
<p>
<span class="line"></span>
<div class="code">
	<a name="assign_key_value_pair_or_ar">assign((key, value pair) or (array(key value pairs))</a>
</div>
<p>	
The method <code>assign()</code> assigns values for variables. In order for a variable in a template to be interpolated it must be assigned. There are two forms which have some important differences. The simple form, is to accept an array and copy all the key/value pairs into an array in FastTemplate. There is only one array in FastTemplate, so assigning a value for the same key will overwrite that key.
<p>
<div class="code">
{CODE_4}
</div>
<p>
<p>
<span class="line"></span>
<div class="code">
	<a name="parse_RETURN_FileHandle_s_">parse(RETURN, FileHandle(s))</a>
</div>
<p>	
The parse function is the main function in FastTemplate. It accepts a new key value pair where the key is the TARGET and the values are the SOURCE templates. There are three forms this can be in:
<p>
<div class="code">
{CODE_5}
</div>
<p> In the regular version, the template named ``main'' is loaded if it hasn't been already, all the variables are interpolated, and the result is then stored in FastTemplate as the value MAIN. If the variable '{MAIN}' shows up in a later template, it will be interpolated to be the value of the parsed ``main'' template. This allows you to easily nest templates, which brings us to the compound style.
<p> The compound style is designed to make it easier to nest templates. The following are equivalent:
<p>
<div class="code">
{CODE_6}
</div>
<p> It is important to note that when you are using the compound form, each template after the first, must contain the variable that you are parsing the results into. In the above example, 'main' must contain the variable '{MAIN}', as that is where the parsed results of 'table' is stored. If 'main' does not contain the variable '{MAIN}' then the parsed results of 'table' will be lost.
<p> The append style allows you to append the parsed results to the target variable. Placing a leading dot <strong>.</strong> before a defined file handle tells FastTemplate to append the parsed results of this template to the returned results. This is most useful when building tables that have an dynamic number of rows - such as data from a database query.
<p>
<p>
<span class="line"></span>
<div class="code">
	<a name="strict_">strict()</a>
</div>
<p>	
When <code>strict()</code> is on (it is on by default) all variables found during template parsing that are unresolved have a warning printed to STDERR;
<p> [FastTemplate] Warning: no value found for variable: SOME_VAR
<p> Also, the variables will be left in the output document. This was done for two reasons: to allow for parsing to be done in stages (i.e. multiple passes), and to make it easier to identify undefined variables since they appear in the parsed output. If you want to replace unknown variables with an empty string, see: <code>no_strict().</code>
<p> Note: STDERR output should be captured and logged by the webserver. With apache (and unix!) you can tail the error log during development to see the results as in;
<pre>        tail -f /var/log/httpd/error_log
</pre>
<p>
<span class="line"></span>
<div class="code">
	<a name="no_strict_">no_strict()</a>
</div>
<p>
Turns off warning messages about unresolved template variables. A call to <code>no_strict()</code> is required to replace unknown variables with an empty string. By default, all instances of FastTemplate behave as is <code>strict()</code> was called. Also, <code>no_strict()</code> must be set for each instance of FastTemplate;
<p>
<div class="code">
{CODE_7}
</div>
<p>
<p>
<span class="line"></span>
<div class="code">
	<a name="FastPrint_HANDLE_">FastPrint(HANDLE)</a>
</div>
<p>	
The method <code>FastPrint()</code> prints the contents of the named variable. If no variable is given, then it prints the last variable that was used in a call to <code>parse()</code> which I find is a reasonable default.
<p>
<div class="code">
{CODE_8}
</div>
<p> This method is provided for convenience. If you need to print somewhere else (a socket, file handle) you would want to <code>fetch()</code> a reference to the data first:
<p>
<pre>    $data = $tpl-&gt;fetch("MAIN");
    fwrite($fd, $data);     // save to a file
</pre>
<p>
<p>
<span class="line"></span>
<div class="code">
	<a name="FastWrite_HANDLE_">FastWrite(HANDLE)</a>
</div>
<p>
The method <code>FastWrite()</code> write the contents of the named variable into a file.
<p>
<pre>    $tpl-&gt;FastWrite();       // continuing from the last example, would
                             // print the value of MAIN
</pre>
<p>
<pre>    $tpl-&gt;FastWrite("MAIN"); // ditto
</pre>
<p> This method is provided for convenience. If you need to print somewhere else (a socket, file handle) you would want to <code>fetch()</code> a reference to the data first:
<pre>    $data = $tpl-&gt;fetch("MAIN");
    fwrite($fd, $data);     // save to a file


To write into a folder, depend on srver configuration.</pre>
<p>
<p>
<span class="line"></span>
<a name="OTHER_METHODS">OTHER METHODS </a>
<p>
<span class="line"></span>
<a name="fetch_HANDLE_">fetch(HANDLE) </a>
Returns the raw data from a parsed handle.
<p>
<pre>    $tpl-&gt;parse(CONTENT, "main");
    $content = $tpl-&gt;fetch("CONTENT");
    print $content;        // print to STDOUT
    fwrite($fd, $content); // write to filehandle
</pre>
<p>
<p>
<span class="line"></span>
<a name="get_assigned_Var_Christian_Bra">get_assigned($Var) Christian Brandel cbrandel@gmx.de </a>
This method will return the value of a variable that has been set via <code>assign().</code> This allows you to easily pass variables around within functions by using the FastTemplate class to handle ``globalization'' of the variables. For example;
<p>
<pre>    $tpl-&gt;assign(  array(  TITLE    =&gt;    $title,
                           BGCOLOR  =&gt;    $bgColor,
                           TEXT     =&gt;    $textColor ));
</pre>
<p>
<pre>    (sometime later...)
    $bgColor = $tpl-&gt;get_assigned(BGCOLOR);
</pre>
<p>
<p>
<span class="line"></span>
<a name="clear_">clear() </a>
Note: All of the <code>clear()</code> functions are for use anywhere where your scripts are persistant. They generally aren't needed if you are writing CGI scripts.
<p> <code>clear()</code> Clears the internal references that store data passed to <code>parse().</code> <code>clear()</code> accepts individual references, or array references as arguments.
<p> Often <code>clear()</code> is at the end of a script:
<p>
<pre>    $tpl-&gt;FastPrint("MAIN");
    $tpl-&gt;clear("MAIN");
</pre>
<p>
<pre>    or
</pre>
<p>
<pre>    $tpl-&gt;FastPrint("MAIN");
    $tpl-&gt;FastPrint("CONTENT");
    $tpl-&gt;clear(array("MAIN","CONTENT"));
</pre>
<p> If called with no arguments, removes ALL references that have been set via <code>parse().</code>
<p>
<p>
<span class="line"></span>
<a name="clear_parse_">clear_parse() </a>
See: <code>clear()</code>
<p>
<p>
<span class="line"></span>
<div class="code">
	<a name="clear_href_KEY_">clear_href(KEY)</a>
</div>	
Removes a given reference from the list of refs that is built using:
<p>
<pre>    $tpl-&gt;assign(KEY = val);
</pre>
<p> If called with no arguments, it removes all references from the array.
<p> (Same as <code>clear_assign()</code> )
<p>
<pre>    $tpl-&gt;assign(    array(    MOVIE  =&gt;  "The Avengers",
                               RATE   =&gt;  "Sucked"    ));
</pre>
<p>
<pre>    $tpl-&gt;clear_href("MOVIE");
    // Now only {RATE} exists in the assign() array
</pre>
<p>
<p>
<span class="line"></span>
<div class="code">
	<a name="clear_define_">clear_define()</a>
</div>
Clears the internal list that stores data passed to:
<p>
<pre>    $tpl-&gt;define();
</pre>
<p> Note: The hash that holds the loaded templates is not touched with this method. ( See: <code>clear_tpl()</code> ) Accepts a single file handle, an array of file handles, or nothing as arguments. If no argument is given, it clears ALL file handles.
<p>
<pre>    $tpl-&gt;define( array( MAIN =&gt; "main.html",
                         BODY =&gt; "body.html",
                         FOOT =&gt; "foot.html"  ));
</pre>
<p>
<pre>    // some code here
</pre>
<p>
<pre>    $tpl-&gt;clear_define("MAIN");
</pre>
<p>
<p>
<span class="line"></span>
<a name="clear_tpl_">clear_tpl() </a>
Clears the internal array that stores the contents of the templates. (If they have been loaded) If you are having problems with template changes not being reflected, try adding this method to your script.
<p>
<pre>    $tpl-&gt;define(MAIN,"main.html" );
    // assign(), parse() etc etc...
</pre>
<p>
<pre>    $tpl-&gt;clear_tpl(MAIN);    // Loaded template now unloaded.
</pre>
<p>
<p>
<span class="line"></span>
<a name="clear_all_">clear_all() </a>
Cleans the module of any data, except for the ROOT directory. Equivalent to:
<p>
<pre>    $tpl-&gt;clear_define();
    $tpl-&gt;clear_href();
    $tpl-&gt;clear_tpl();
    $tpl-&gt;clear_parse();
</pre>
<p> In fact, that's exactly what it does.
<p>
<p>
<span class="line"></span>
<a name="Variables">Variables </a>
A variable is defined as:
<p>
<pre>    {([A-Z0-9_]+)}
</pre>
<p> This means, that a variable must begin with a curly brace '{'. The second and remaining characters must be uppercase letters or digits 'A-Z0-9'. Remaining characters can include an underscore. The variable is terminated by a closing curly brace '}'.
<p> For example, the following are valid variables:
<pre>    {FOO}
    {F123F}
    {TOP_OF_PAGE}
</pre>
<p>
<span class="line"></span>
<a name="Variable_Interpolation_Template">Variable Interpolation (Template Parsing) </a>
If a variable cannot be resolved to anything, a warning is printed to STDERR. See <code>strict()</code> and <code>no_strict()</code> for more info.
<p> Some examples will make this clearer.
<p>
<pre>    Assume:
</pre>
<p>
<pre>    $FOO = "foo";
    $BAR = "bar";
    $ONE = "1";
    $TWO = "2";    
    $UND = "_";
    
    Variable    Interpolated/Parsed
    ------------------------------------------------
    {FOO}            foo    
    {FOO}-{BAR}      foo-bar
    {ONE_TWO}        {ONE_TWO} // {ONE_TWO} is undefined!    
    {ONE}{UND}{TWO}  1_2
    ${FOO}           $foo
    $25,000          $25,000
    {foo}            {foo}     // Ignored, it's not valid, nor will it
                               // generate any error messages.
</pre>
<p>
<p>
<span class="line"></span>
<a name="FULL_EXAMPLE">FULL EXAMPLE </a>
This example will build an HTML page that will consist of a table. The table will have 3 numbered rows. The first step is to decide what templates we need. In order to make it easy for the table to change to a different number of rows, we will have a template for the rows of the table, another for the table, and a third for the head/body part of the HTML page.
<p> Below are the templates. (Pretend each one is in a separate file.)
<p>
<pre>  &lt;!-- name: main.html --&gt;
  &lt;html&gt;
  &lt;head&gt;&lt;title&gt;{TITLE}&lt;/title&gt;
  &lt;/head&gt;
  &lt;body&gt;
  {MAIN}
  &lt;/body&gt;
  &lt;/html&gt;
  &lt;!-- END: main.html --&gt;
 
 
  &lt;!-- name: table.html --&gt;
  &lt;table&gt;
  {ROWS}
  &lt;/table&gt;
  &lt;!-- END: table.html --&gt;
 
 
  &lt;!-- name: row.html --&gt;
  &lt;tr&gt;
  &lt;td&gt;{NUMBER}&lt;/td&gt;
  &lt;td&gt;{BIG_NUMBER}&lt;/td&gt;
  &lt;/tr&gt;
  &lt;!-- END: row.html --&gt;
</pre>
<p> Now we can start coding...
<p>
<pre> /* START */</pre>
<pre>    &lt;?
    include("cls_fast_template.php");
    $tpl = new FastTemplate("/path/to/templates");
    $tpl-&gt;define( array( main   =&gt; "main.html",
                         table  =&gt; "table.html",
                         row    =&gt; "row.html"    ));
</pre>
<p>
<pre>    $tpl-&gt;assign(TITLE,"FastTemplate Test");
</pre>
<p>
<pre>    for ($n=1; $n &lt;= 3; $n++)
    {
        $Number = $n;
        $BigNum = $n*10;
        $tpl-&gt;assign( array(  NUMBER      =&gt;  $Number,
                              BIG_NUMBER  =&gt;  $BigNum ));
</pre>
<p>
<pre>        $tpl-&gt;parse(ROWS,".row");
    }
    $tpl-&gt;parse(MAIN, array("table","main"));
    Header("Content-type: text/plain");
    $tpl-&gt;FastPrint();
    exit;
    ?&gt;
</pre>
<p>
<pre>  When run it returns:
</pre>
<p>
<pre>  &lt;!-- name: main.html --&gt;
  &lt;html&gt;
  &lt;head&gt;&lt;title&gt;FastTemplate Test&lt;/title&gt;
  &lt;/head&gt;
  &lt;body&gt;
  &lt;!-- name: table.html --&gt;
  &lt;table&gt;
  &lt;!-- name: row.html --&gt;
  &lt;tr&gt;
  &lt;td&gt;1&lt;/td&gt;
  &lt;td&gt;10&lt;/td&gt;
  &lt;/tr&gt;
  &lt;!-- END: row.html --&gt;
  &lt;!-- name: row.html --&gt;
  &lt;tr&gt;
  &lt;td&gt;2&lt;/td&gt;
  &lt;td&gt;20&lt;/td&gt;
  &lt;/tr&gt;
  &lt;!-- END: row.html --&gt;
  &lt;!-- name: row.html --&gt;
  &lt;tr&gt;
  &lt;td&gt;3&lt;/td&gt;
  &lt;td&gt;30&lt;/td&gt;
  &lt;/tr&gt;
  &lt;!-- END: row.html --&gt;
  
  &lt;/table&gt;
  &lt;!-- END: table.html --&gt;
</pre>
<p>
<pre>  &lt;/body&gt;
  &lt;/html&gt;
  &lt;!-- END: main.html --&gt;
</pre>
<p> If you're thinking you could have done the same thing in a few lines of plain PHP, well yes you probably could. But, how would a graphic designer tweak the resulting HTML? How would you have a designer editing the HTML while you're editing another part of the code? How would you save the output to a file, or pipe it to another application? How would you make your application multi-lingual? How would you build an application that has options for high graphics, or text-only? FastTemplate really starts to shine when you are building mid to large scale web applications, simply because it begins to seperate the application's generic logic from the specific implementation.
<h1><a name="AUTHOR">AUTHOR </a></h1>
<p>Thanx to some programmers (see below) </p>
<p>Artyem V. Shkondin aka AiK <a href="mailto:artvs@clubpro.spb.ru">artvs@clubpro.spb.ru</a> <br>
  Allyson Francisco de Paula Reis <a href="mailto:ragen@oquerola.com">ragen@oquerola.com</a><br>
  GraFX Software Solutions <a href="mailto:webmaster@grafxsoftware.com">webmaster@grafxsoftware.com</a> <br>
  Wilfried Trinkl <a href="mailto:wisl@gmx.at">wisl@gmx.at</a> <br>
  CDI <a
href="MAILTO:cdi@thewebmasters.net">cdi@thewebmasters.net</a></p>
<p>Original Perl module CGI::FastTemplate by Jason Moore <a
href="MAILTO:jmoore@sober.com">jmoore@sober.com</a> </p>
<p> PHP3 Version Copyright (c) 1999 CDI, <a
href="MAILTO:cdi@thewebmasters.net,">cdi@thewebmasters.net,</a> All Rights Reserved.
<p> Perl Version Copyright (c) 1998 Jason Moore <a
href="MAILTO:jmoore@sober.com.">jmoore@sober.com.</a> All Rights Reserved.
<p> This program is free software; you can redistribute it and/or modify it under the GNU General Artistic License, with the following stipulations;
<p> Changes or modifications must retain these Copyright statements. Changes or modifications must be submitted to both AUTHORS.
<p> This program is released under the General Artistic License.
<p> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the Artistic License for more details. This software is distributed AS-IS.
<p> Address Bug Reports or Comments on <strong>THIS PHP VERSION ONLY</strong> to
<pre>    GraFX Software Solutions <a href="mailto:webmaster@grafxsoftware.com">webmaster@grafxsoftware.com</a> </pre>
<p> The latest version of this class should be available from the following locations:
<p> <a
href="http://www.grafxsoftware.com">http://www.grafxsoftware.com/</a>
<p>
<span class="line"></span>
<h1><a name="DOCUMENTATION">DOCUMENTATION </a></h1>
Sascha Schumann has written a very nice FastTemplate tutorial. It's on the PHPBuilder.com web site at;
<p> <a href="http://www.phpbuilder.com/">http://www.phpbuilder.com/</a>
<p> This is a modified version of the CGI::FastTemplate man page, originally written by Jason Moore <a
href="MAILTO:jmoore@sober.com.">jmoore@sober.com.</a> Forgive me if I didn't get all the Perlisms out of the example code.
<p> This is not a complete port, the <code>define_nofile(array()),</code> and/or <code>define_raw(array())</code> methods were not implemented in this port since I had no need or use for them. Some of the methods are implemented differently (mostly due to PHP's stronger variable type requirements.) The functionality of each method has remained the same. The <code>define_dynamic()</code> method is completely new to this PHP port and does not appear in the Perl version.
<p> The variable declaration method has changed from the Perl version's $(A-Z0-9_)+ to {(A-Z0-9_)+}, which means you'll have to edit all your templates. The beginning and close curly braces allow for much faster and more accurate templates.
<p>
<p>
<span class="line"></span>
<h1><a name="SEE_ALSO">SEE ALSO </a></h1>
CGI::FastTemplate Perl module, available from CPAN - <a
href="http://www.cpan.org">http://www.cpan.org</a>
<p>
  </dl>
</body>
</html>
