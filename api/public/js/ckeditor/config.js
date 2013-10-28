/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'it';
	config.contentsLanguage = 'it';
	
	// config.uiColor = '#AADC6E';
	config.toolbar_IRIS =
	[
		//{ name: 'testing', items : ['Source']},
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : ['SelectAll','-','SpellChecker', 'Scayt' ] },
		{ name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar' ] },
		{ name: 'tools', items : [ 'Maximize' ] },
		'/',
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Blockquote', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
		{ name: 'links', items : [ 'Link','Unlink' ] },
		'/',
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] }
	];

	config.toolbar = 'IRIS';
	config.htmlEncodeOutput = false;
	config.fullPage = false;
	config.docType = "<!DOCTYPE html>";
	
	config.protectedSource.push( "/<\?[\s\S]*?\?>/g" );   // PHP code
	config.protectedSource.push( "/<%[\s\S]*?%>/g" );   // ASP code
	config.protectedSource.push( "/(]+>[\s|\S]*?<\/asp:[^\>]+>)|(]+\/>)/gi" );   // ASP.Net code
};
