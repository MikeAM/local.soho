<style type="text/css">


form, ul {padding: 0px; margin: 0px;}
ul li {
	list-style: none;
}
textarea {
	width: 500px;
}
label {
	font-size: 14px;
}
.input {width: 500px;}
#categories, #newPost {
	margin-bottom: 10px;
	border: 1px solid #222;
}
#categories {
	background: #E3D9D3;
	width: 250px;
	margin-left: 15px;
}
#leftContainer {
	width: 70%;
	min-width: 535px;
}
#newPost {
	background: #E3D9D3;
	width: 100%;
}

select {
	background: #FFFFFF;
	color: #464646;
	border: 1px solid #7F9DB9;
	font: normal 16px Arial,Helvetica,sans-serif;
	width: 200px;
}

#authors:hover,
#authors:focus,
#del_subj:hover,
#del_subj:focus {
	opacity: 1;
}
#plusTag {
	background: url('graphics/sm_add.png') top left no-repeat;
	display: block;
	width: 24px;
	height: 29px;
}
.postTable {
	width: 98%;
	border: 1px solid #7D8084;
}
 
.fsub_title, .postTable th {
	border-bottom: 1px solid #7D8084;
	background:#d2d2d2 url(../../includes/images/title-bg.png) repeat-x;
	padding:4px;
	text-align: left;
	font: italic normal bold 16px Arial,Helvetica,sans-serif;
	text-shadow: 0px 1px 1px #f8f8f8;
	color: #444;
}
.postTable td {
	padding: 5px 5px;
	text-align: left;
	font: italic normal bold 12px Arial,Helvetica,sans-serif;
	text-shadow: 0px 1px 1px #f8f8f8;
	color: #666;
}
.light {
	background: #fff;
}
.dark {
	background: #f5f5f5;
}
.darker {
	background: #f0f0f0;
}

input[type=password],
input[type=text]{
	background: #F1EBEB;
	border: 1px solid #7D8084;
	padding: 5px;
	margin: 3px 0px;
	font: italic normal bold 14px Arial,Helvetica,sans-serif;
	/* text-shadow: 0px 1px 1px #f8f8f8; */
	color: #888;
}

.catNames {
	padding: 1px 0px 1px 40px;
	font-size: 16px;
	font-weight: bold;
	text-shadow: 0px 1px 1px #f8f8f8;
	color: #555;
}
.catNames a:link {text-decoration: none; padding: 0px; margin: 0px;}
.catNames a:visited {text-decoration: none;}
.catNames a:hover {text-decoration: underline;}
.catNames a:active {text-decoration: none;}
.saveTitle {
	width:44px;
	height:14px;
	border: none;
	cursor:pointer;
}
.entry div.content {
	
	border:1px solid #aaa;
	background: #ffffff;
	padding: 8px;
	margin: 10px;
}
.tags {
	display: block;
}
.tag {
	display: inline;
	position: relative;
	padding: 5px 8px;
	margin: 0;
	white-space: nowrap;
}
.tstart {
	background: url('graphics/tstart.png') top left no-repeat;
	float: left;
	height: 29px;
	width: 19px;

	/* IE Only */
	*float: none;
	*display: inline;
	zoom: 1;
}
.ttag {
	background: url('graphics/tbody.png') top left;
	color: #eee;
	float: left;
	text-shadow: 0px -1px 1px #555;
	font-weight: bold;
	font-size: 11px;
	padding: 8px 0px 8px 0px;
	/* IE Only */
	*float: none;
	*display: inline;
	zoom: 1;
}
.tend {
	background: url('graphics/tend.png') top left no-repeat;
	float: left;
	height: 29px;
	width: 22px;
	/* IE Only */
	*float: none;
	*display: inline;
	zoom: 1;

	position: relative;
}
.addTag {
	display: inline;
	position: relative;
	padding: 5px 8px;
	margin: 0;
	white-space: nowrap;
}
.removeTag {
	display: none;
	background: url('graphics/sm_remove.png') top left no-repeat;
	position: absolute;
	width: 20px;
	height: 20px;
	cursor: pointer;
}
.delImage {
	padding: 0px 20px 0px 0px;
	border: none;
	vertical-align: middle;
	width: 44px;
	height: 14px;
	cursor: pointer;
	display: none;
	background: url('graphics/delete.png') top left no-repeat;
}

span.inputField input {
	border: none;
	background: transparent;
	outline: none;
	overflow: visible;
}
span.inputField {
	position: relative;
	background: transparent;
	vertical-align: middle;
	border: 0px solid #7D8084;
	padding: 5px;
}
.entry span.datestamp {
	float: right;
	text-align: right;
	color: #555;
	font-style: italic;
	font-size: 10px;

}
span img {
	vertical-align: text-top;
}
.inputField input[type=text], label
{
	font: italic normal bold 10px Arial,Helvetica,sans-serif;
	text-shadow: 0px 1px 1px #f8f8f8;
	vertical-align: middle;
	color: #555;
}
.blog_labels{
	padding: 5px 5px 5px 5px;
	font: italic normal bold 16px Arial,Helvetica,sans-serif;
	text-shadow: 0px 1px 1px #f8f8f8;
	color: #888;
}
.indented {
	margin-left: 40px;
}
.pointer {
	cursor: pointer;
}
.clear {clear: both;}
.highlight {
	background: red;
}
.right, .floatRight {
	float: right;
}
.left, .floatLeft {
	float: left;
}
.nopadding {
	padding: 0px !important;
}
 ul.nopadding li {
 	padding: 10px 0px;
 }
.inline {
	display: inline;
}
.error {
	border: 1px solid red !important;
}
.errorText {
	padding-left: 10px;
	font-variant: small-caps;
	font-size: 12px;
	font-weight: bold;
	color: red;
	margin:0;
}
.note {
	font-size: 10px;
	color: #888;
	font-style: italic;
}
.pform {
	margin:2px;
}

.blogtables {
	border:0px solid black;
	vertical-align:top;
}
</style>
