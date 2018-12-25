<?php
	// COOKİE OLUŞTUR
	# Ip adresi artı rasgele bir sayı atanır.
	if(!$_COOKIE["cookie"]){
		$ip=$_SERVER["REMOTE_ADDR"];
		$ip=str_replace(":","",$ip);
		$ip.= rand(1,1000);
		setcookie("cookie",$ip);
	}
	// SON - COOKİE OLUŞTUR
?>
<html>
<head>
	<meta charset="utf-8"/>
	<style type="text/css">
		body{
			background-color: #F8F8F8;
			width: 1000px;
			position: relative;
			margin: auto;
		}
		.header{
			background-color: white;
			width: 960;
			border: 1px double #CFCFCF;
			height: 120;
			position: relative;
			top: 10px;
		}
		.main-frame{
			background-color: white;
			width: 860;
			border: 1px double #CFCFCF;
			min-height: 340;
			padding: 50px 50px 0px 50px;
			position: relative;
			top: 20px;
		}
		.footer{
			background-color: white;
			width: 960;
			border: 1px double #CFCFCF;
			height: 60;
			position: relative;
			top: 30px;
		}
		div .footer-text{
			text-decoration: none;
			font-size: 36;
			color: black;
		}
		.footer-text:hover{
			text-decoration: none;
			font-size: 36;
			color: black;
		}
		.footer-text:visited{
			text-decoration: none;
			font-size: 36;
			color: black;
		}
		.title{
			color: black;
			font-size: 42px;
			font-family: Helvetica Narrow, sans-serif;
			left: 20px;
			top: 30px;
			background-color: cyan;
			position: relative;
		}
		.title2{
			color: black;
			font-size: 36px;
			font-family: Helvetica Narrow, sans-serif;
			background-color: cyan;
			position: relative;
			float: right;
		}
		a{
			color: black;
			font-size: 20;
			font-family: Times New Roman, serif;
		}
		ul.bot-list{
			list-style-type: none;
			margin: 200px 0px 40px 0px;
			position: relative;
		}
		ul.bot-list li{
			display: inline;
			margin: 100px 0px 0px 10px;
		}
		ul.bot-list li a{
			text-decoration: none;
			color: black;
			border-bottom: solid 1px #CFCFCF;
		}
	</style>
</head>
<body>
	<div class="header">
		<a class="title">&nbsp;&nbsp;SlidesharetoPdf&nbsp;&nbsp;&nbsp;</a>
		<a class="title2">&nbsp;&nbsp;Beta&nbsp;&nbsp;</a>
	</div>
	<div class="main-frame">
	<form action="" method="post">
		<table>
			<tr>
				<td>
					<input style="height: 30px; " placeholder="Slideshare link" size="115" name="links"/><br />
				</td>
			</tr>
			<tr>
				<td>
					<input placeholder="Total page number" maxlength="3" size="10" type="text" name="pages"/>
					<input type="radio" value="1" name="type"/>Slide
					<input type="radio" value="2" name="type"/>Document
				</td>
			</tr>
			<tr>
				<td><input style="" type="submit" value="Send"/></td>
				<td></td>
				<td></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
	<?php
		//DEGERLER DEĞIŞKENLERE ATANIR.
		if(isset($_POST["links"])){
			$list=$_POST["links"];
			$list=trim($list);
			$list=str_replace("1-638.jpg","1-1024.jpg",$list);
			if(strstr($list,"slideshare")){
				$c_list=True;
			}else{
				echo "<a>Wrong link.</a>";
				$c_list=False;
			}
			$link_type=$_POST["type"];
			if(is_numeric($_POST["pages"])){ #Numara kontrolü
				$pages=$_POST["pages"];
				$pages=trim($pages);
				settype($pages,integer);
				$pages+=1;
				$c_pages=True;
			}elseif($_POST["pages"] == ""){
				echo "<br /><a>Total page number can't be empty.";
				$c_pages=False;
			}elseif(!is_numeric($_POST["pages"])){
				echo "<br /><a>Total page number wrong input, have to be number.";
				$c_pages=False;
			}
			if(isset($_POST["type"])){ #Tip kontrolü
				$c_type=True;
			}elseif(!isset($_POST["type"])){
				echo "<br /><a>You have to pick a slide type.";
				$c_type=False;
			}
		}
		function KlasorSil($dir) {
			if (substr($dir, strlen($dir)-1, 1)!= '/')
			$dir .= '/';
			//echo $dir; //silinen klasörün adı
			if ($handle = opendir($dir)) {
				while ($obj = readdir($handle)) {
					if ($obj!= '.' && $obj!= '..') {
						if (is_dir($dir.$obj)) {
							if (!KlasorSil($dir.$obj))
								return false;
							} elseif (is_file($dir.$obj)) {
								if (!unlink($dir.$obj))
									return false;
								}
						}
				}
					closedir($handle);
					if (!@rmdir($dir))
					return false;
					return true;
				}
			return false;
		}  
		@$jpg_file_jpg="images/" . $_COOKIE["cookie"] . "/" . "1.jpg";
		@$jpg_dir_jpg="images/" . $_COOKIE["cookie"];
		if(file_exists($jpg_file_jpg)){
			KlasorSil($jpg_dir_jpg);
		}
		// RESİMLERİ İNDİR
		if($c_list and $c_pages and $c_type){
			$c_write=True;$c_open=True;
			$locate="images/";
			$locate.=$_COOKIE["cookie"];
			@mkdir($locate, 0755);
			for($x=1;$x<$pages;$x++){
				$sirali= "slide-" . $x . "-";
				$kayit_yeri= "images/" . $_COOKIE["cookie"] . "/" . $x . ".jpg";
				$list_last=str_replace("slide-1-",$sirali,$list);
				@$link=file_get_contents($list_last);
				if($b=fopen($kayit_yeri,'w')){
					if(fwrite($b,$link)){
						fclose($b);
					}else{
						echo '<a>Wrong or dead link, please check your address.';
						$c_write=False;
						break;
					}
				}else{
					echo '<a>System error 0x01'; ##Resim yazmak için açılamadı.
					$c_open=False;
					break;
				}
			}
		}
		// SON - RESİMLERİ İNDİR
		//
		// PDF OLUŞTUR
		if($c_write and $c_open and $c_list){
			@$jpg_file_pdf="pdffile/" . $_COOKIE["cookie"] . ".pdf";
			if(file_exists($jpg_file_pdf)){
				unlink($jpg_file_pdf);
			}
			@list($wid,$hei)=getimagesize("images/" . $_COOKIE["cookie"] . "/" . "1" . ".jpg");
			switch ($link_type){
				case "1":
					require('fpdf/fpdf.php');
					$outp="pdffile/" . $_COOKIE["cookie"] . ".pdf";
					$pdf = new FPDF("L","pt",array($wid,$hei));
					for($x=1;$x<$pages;$x++){
						$file_name="images/" . $_COOKIE["cookie"] . "/" . $x . ".jpg";
						$pdf->AddPage();
						$pdf->Image($file_name,"0","0",$wid,$hei);
					}
					$pdf->Output($outp);
					echo "<a>Pdf ready.";
					$c_pdf=True;
				break;
				case "2":
					require('fpdf/fpdf.php');
					$outp="pdffile/" . $_COOKIE["cookie"] . ".pdf";
					$pdf = new FPDF("P","pt",array($wid,$hei));
					for($y=1;$y<$pages;$y++){
						$file_name="images/" . $_COOKIE["cookie"] . "/" . $y . ".jpg";
						$pdf->AddPage();
						$pdf->Image($file_name,"0","0",$wid,$hei);
					}
					$pdf->Output($outp);
					echo "<a>Pdf ready.";
					$c_pdf=True;
				break;
			}
		}
		// SON - PDF OLUŞTUR
		$pdfpath="pdffile/" . $_COOKIE["cookie"] . ".pdf";
		if ($c_pdf){
			echo "<br /><a href=\"$pdfpath\">Download</a>";
		}
?>
	<ul class="bot-list">
		<li><a href="howto.php">How to</a></li>
		<li><a href="contack.php">Contact</a></li>
		<li><a href="donate.php">Donate</a></li>
	</ul>
	</div>
	<div style="text-align: center; padding-top: 20px;" class="footer">
		<a class="footer-text" href="http://selimakpinar.com">©Selim Akpınar</a>
	</div>
</body>
</html>
