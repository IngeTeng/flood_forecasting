/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {

	//配置文件的参数可以访问http://docs.ckeditor.com/#!/api/CKEDITOR.config
	//这里是通用配置。可以在加载的时候进行自定义配置

	config.language = 'zh-cn';
	//config.uiColor = '#F7B42C';//颜色
	config.width = '100%';
	config.height = 300;
	//config.toolbarCanCollapse = true;//工具条能否收起
	//工具条--最简单
	config.toolbar_Basic = [
		['Paste', 'PasteText', 'PasteFromWord','-', 'Undo', 'Redo'],['Bold','Italic','Underline'],['NumberedList', 'BulletedList'],['JustifyLeft','JustifyCenter','JustifyRight'],['Link', 'Unlink']
	];
	//工具条--最常用
	config.toolbar_Common =[
        ['Paste', 'PasteText', 'PasteFromWord','-', 'Undo', 'Redo'],['Bold','Italic','Underline'],['NumberedList','BulletedList'],['JustifyLeft','JustifyCenter','JustifyRight','-','Outdent','Indent'],
        '/',
        ['TextColor','BGColor','Format'],['Link','Unlink','Anchor'],['Image', 'Table', 'SpecialChar'],['Source'],['Maximize']
    ];

	//定义上传处理文件//留空表示禁止上传
	config.filebrowserUploadUrl = 'ckeditor_upload.php?type=file';
	config.filebrowserImageUploadUrl= "ckeditor_upload.php?type=img";

	// Set the most common block elements.显示在toolbar中“格式”下的样式
	config.format_tags = 'p;h1;h2;h3';

	// Simplify the dialog windows.简化弹出框
	config.removeDialogTabs = 'image:advanced;link:advanced';
	
	//是否强制粘贴为纯文本格式
	config.forcePasteAsPlainText = false;
	
};