div.body {
	width: 100%;
}
div.body div.upper {
	border-bottom: 1px solid #c7c7c7;
	margin-bottom: 1px;
}
div.body div.upper span.title {
	font-family: arial;
	font-size: 14pt;
	color: #9b9696;
}
div.body div.upper div.subheading {
	font-family: arial;
	font-size: 10pt;
	font-weight: bold;
	float: right;
	color: #838080;
	position: relative;
	bottom: 15px;
}
div.body div.leftbar {
	padding: 10px 30px 20px 0;
	float: left;
	font-size: 10pt;
	font-family: tahoma;
}
div.body div.rightbar {
	padding: 10px 0 0 10px;
}
div.body div.leftbar div.folders {
	border: 1px solid #838080;
	max-width: 400px;
}
div.body div.leftbar div.folders div.activefolder {
	font-weight: bold;
	background-color: #d6e6f7;
	background-image: url( 'images/folder.png' );
	background-position: 3px 3px;
	background-repeat: no-repeat;
	padding: 3px 3px 3px 22px;
}
div.body div.leftbar div.folders div.folder {
	border-top: 1px solid #d7d7d7;
	background-image: url( 'images/folder.png' );
	background-position: 3px 3px;
	background-repeat: no-repeat;
	padding: 3px 3px 3px 22px;
}
div.body div.leftbar div.folders div.newfolder {
	border-top: 1px solid #d7d7d7;
	background-image: url( 'images/folder_add.png' );
	background-position: 3px 3px;
	background-repeat: no-repeat;
	padding: 3px 3px 3px 22px;
}
a.folderlinksactive , a.folderlinksactive:active , a.folderlinksactive:visited , a.folderlinksactive:hover {
	text-decoration: none;
}
a.folderlinks , a.folderlinks:active , a.folderlinks:visited , a.folderlinksactive:hover {
	text-decoration: none;
}
a.folderlinksnew , a.folderlinksnew:active , a.folderlinksnew:visited , a.folderlinksnew:hover {
	color: #d0cfcf;
	text-decoration: none;
}
div.messages {
	//margin: 0 40px 0 40px;
}
div.messages div.message {
	position: relative;
	margin-bottom: 15px;
}
div.messages div.message div.infobar {
	height: 21px;
	border: 1px solid #cdcdcb;
	padding-left: 3px;
}
div.messages div.message div.infobar div.infobar_info {
	padding: 3px;
	height: 21px;
	cursor: pointer;
}
div.messages div.message div.text {
	background-color: #f8f8f6;
	border-left: 1px solid #cdcdcb;
	border-right: 1px solid #cdcdcb;
	padding-right: 60px;
	overflow: auto;
}
div.messages div.message div.text div {
	padding: 10px 5px 0px 5px;
}
div.messages div.message div.lowerline {
	height: 13px;
	position: relative;
}
div.messages div.message div.lowerline div.leftcorner {
	background-image: url( 'images/pm-downleft.jpg' );
	background-repeat: no-repeat;
	background-position: bottom left;
	height: 13px;
	width: 10px;
	float: left;
}
div.messages div.message div.lowerline div.middle {
	background-image: url( 'images/pm-downfade.jpg' );
	background-repeat: repeat-x;
	background-position: bottom;
	height: 13px;
}
div.messages div.message div.lowerline div.rightcorner {
	background-image: url( 'images/pm-downright.jpg' );
	background-repeat: no-repeat;
	background-position: bottom right;
	height: 13px;
	width: 10px;
	float: right;
}
div.messages div.message div.toolbar {
	position: absolute;
	right: 9px;
	bottom: 3px;
}
div.messages div.message div.toolbar ul {
	display: block;
	height: 16px;
	margin: 0;
	padding: 0;
}
div.messages div.message div.toolbar ul li {
	display: inline;
	background-image: url( 'images/commentbox-toolbarleft.jpg' );
	background-repeat: no-repeat;
	background-position: bottom left;
	background-color: #dedddb;
	padding: 0 0 2px 7px;
	margin: 0 2px 0 2px;
	float: right;
}
div.messages div.message div.toolbar ul li input.submit {
	background-image: url( 'images/commentbox-toolbarright.jpg' );
	background-repeat: no-repeat;
	color: #858583;
	cursor: pointer;	
}
div.messages div.message div.toolbar ul li  input.submit:hover {
	text-decoration: none;
	color: black;
}

div.messages div.message div.toolbar ul li  a {
	background-image: url( 'images/commentbox-toolbarright.jpg' );
	background-repeat: no-repeat;
	background-position: bottom right;
	color: #858583;
	padding: 2px 7px 2px 0;
	cursor: pointer;
}
div.messages div.message div.toolbar ul li  a:hover {
	text-decoration: none;
	color: black;
}
a.folder_links , a.folderlinks:active , a.folderlinks:visited , a.folderlinks:hover {
	text-decoration: none;
}