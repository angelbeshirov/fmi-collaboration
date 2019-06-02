<?php
	require 'util.php';
	shouldRedirectNotLoggedIn();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Online Collaboration</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" media="screen" href="css/my_files.css">

	<script type="text/javascript" src="js/navigation.js" defer></script>
	<script type="text/javascript" src="js/my_files.js" defer></script>
	<script type="text/javascript" src="js/rest.js"></script>
	
</head>
<body id="gradient">
	<nav id="navigation-bar">
	</nav>
	<section>
		<div id="files-container">
			<div class="limiter">
				<div class="table-container">
					<div class="wrap-table"></div>
						<div class="files-table">
							<div class="header">
								<div class="selected-file-wrapper">
									Избран файл: 
									<input type="text" id="selected-file" readOnly>
								</div>
								<a class='button btn-blue'>Download</a>
								<a class='button btn-blue'>Upload</a>
								<a class='button btn-blue'>Edit</a>
								<a class='button btn-blue'>Share with</a>
								<a class='button btn-blue'>Delete</a>
							</div>
							<table id="main-table">
								<thead>
									<tr class="table-head">
										<th class="column1">Файл</th>
										<th class="column2">Размер</th>
										<th class="column3">Качен на</th>
										<th class="column4">Последна промяна на</th>
										<th class="column5">Вид</th>
										<th class="column6">Действие</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s12.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t123123.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">123213.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t1213s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">adasd.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">asdasdasd.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">asd.pptx</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Презентация</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h">
												<div class="dropdown-menu">
													<a href="#">Изтегли</a>
													<a href="#">Отвори</a>
													<a href="#">Сподели с</a>
													<a href="#">Изтрий</a>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h">
												<div class="dropdown-menu ">
													<a href="#">Изтегли</a>
													<a href="#">Отвори</a>
													<a href="#">Сподели с</a>
													<a href="#">Изтрий</a>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
									<tr>
										<td class="column1">t3s7.txt</td>
										<td class="column2">150 KB</td>
										<td class="column3">2017-09-28 05:57</td>
										<td class="column4">2018-09-28 05:57</td>
										<td class="column5">Текстов файл</td>
										<td class="column6">
											<div class="file-menu fas fa-ellipsis-h"></div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</body>
</html>
