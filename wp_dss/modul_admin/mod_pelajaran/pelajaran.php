<?php
$aksi="modul_admin/mod_pelajaran/aksi_pelajaran.php";
$jlhkriteria=mysql_result(mysql_query("SELECT COUNT(*) as Num FROM kriteria"),0);
switch($_GET[act]){
  // Tampil Kelas
  default:
    echo "<h2>Evaluasi Penjurusan SMA</h2>
          <table>
          <tr><th>id_kelas</th><th>nama siswa</th><th>aksi</th></tr>"; 
  // Paging
  	$hal = $_GET[hal];
	if(!isset($_GET['hal'])){ 
		$page = 1; 
		$hal = 1;
	} else { 
		$page = $_GET['hal']; 
	}
	$jmlperhalaman = 10;  // jumlah record per halaman
	$offset = (($page * $jmlperhalaman) - $jmlperhalaman);
    $edit=mysql_query("SELECT s.nis,s.nama_siswa FROM siswa s, kelas k
	WHERE k.wali_kelas='$_SESSION[namauser]' AND s.sts='N' AND s.id_kelas=k.id_kelas 
	ORDER BY s.nis,s.nama_siswa ASC 
	LIMIT $offset, $jmlperhalaman");
    while ($r=mysql_fetch_array($edit)){
       echo "<tr>
			 <td>$r[nis]</td>
             <td>$r[nama_siswa]</td>
			 <td><input type=button value='evaluasi penjurusan' 
          onclick=\"window.location.href='?modul=pelajaran&act=input&id=$r[nis]';\"></td>
			 ";
	   echo "</tr>";
    }
    echo "</table>";
	// membuat nomor halaman
	$total_record = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM siswa s, kelas k WHERE k.wali_kelas='$_SESSION[namauser]' AND s.sts='N' AND s.id_kelas=k.id_kelas"),0);
	$total_halaman = ceil($total_record / $jmlperhalaman);
	echo "<center>Halaman :<br/>"; 
	$perhal=4;
	if($hal > 1){ 
		$prev = ($page - 1); 
		echo "<a href=indexs.php?modul=pelajaran&act=view&hal=$prev> << </a> "; 
	}
	if($total_halaman<=10){
	$hal1=1;
	$hal2=$total_halaman;
	}else{
	$hal1=$hal-$perhal;
	$hal2=$hal+$perhal;
	}
	if($hal<=5){
	$hal1=1;
	}
	if($hal<$total_halaman){
	$hal2=$hal+$perhal;
	}else{
	$hal2=$hal;
	}
	for($i = $hal1; $i <= $hal2; $i++){ 
		if(($hal) == $i){ 
			echo "[<b>$i</b>] "; 
			} else { 
		if($i<=$total_halaman){
				echo "<a href=indexs.php?modul=pelajaran&act=view&hal=$i>$i</a> "; 
		}
		} 
	}
	if($hal < $total_halaman){ 
		$next = ($page + 1); 
		echo "<a href=indexs.php?modul=pelajaran&act=view&hal=$next>>></a>"; 
	} 
	echo "</center><br/>";
  break;	
      
  case "input":
    $jlhjurusan = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM jurusan"),0); // Jumlah Jurusan
    $jlhkriteria = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM kriteria"),0); // Jumlah Kriteria
    $edit=mysql_query("SELECT * FROM siswa WHERE nis='$_GET[id]'");
    $r=mysql_fetch_array($edit);
    echo "<h2>Validasi Penilaian Siswa</h2>
          <form method=POST action=$aksi?modul=pelajaran&act=input>
          <input type=hidden name=id value='$r[nis]'>
          <table>
		  <tr><td>Id_Kelas</td> <td> : <input type=text readonly name='id_kelas' size=30 value='$r[id_kelas]'></td></tr>
		  <tr><td>NIS</td> <td> : <input type=text readonly name='nis' size=30 value='$r[nis]'></td></tr>
          <tr><td>Nama Lengkap</td> <td> : <input type=text name='nama_siswa' size=30  value='$r[nama_siswa]'></td></tr>";
	for ($i=1; $i<=$jlhjurusan; $i++){
		$queri1=mysql_query("SELECT * FROM jurusan WHERE id_jurusan='$i' ORDER BY id_jurusan ASC");
		$r3=mysql_fetch_array($queri1);
		for ($j=1; $j<=$jlhkriteria; $j++){
			$queri2=mysql_query("SELECT * FROM kriteria WHERE id_kriteria='$j' ORDER BY id_kriteria ASC");
			$kriteria2=mysql_fetch_array($queri2);
			echo "<tr>";
			echo "
			<td>
			<input type=hidden name='id_jurusan".$i.$j."' value='$r3[id_jurusan]'>
			$kriteria2[nama_kriteria] $r3[nama_jurusan]<input type=hidden name='id_kriteria".$i.$j."' value='$kriteria2[id_kriteria]'>
			<input type=hidden name='nama_kriteria".$i.$j."' value='$kriteria2[nama_kriteria]'>
			</td>
			<td> :"; 
			if (($j==2) OR ($j==5)){
				echo "<input type=text readonly name='m".$i.$j."'>";
			}else { echo "<input type=text name='m".$i.$j."'>"; }
			echo "</td>
				 ";
			echo "</tr>";
		}
	}
    echo "
          <tr><td colspan=2><input type=submit value=Update>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
    break;  

}

?>
