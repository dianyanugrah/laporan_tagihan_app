<?php
  $no = 1;
  foreach ($dataLaporan as $laporan) {
    ?>
    <tr>
      <td><?php echo $no; ?></td>
      <td><?php echo $laporan->no_ppk; ?></td>
      <td><?php echo $laporan->no_agenda; ?></td>
      <?php if ($laporan->no_spm == '')  { ?>
       <td> - </td>
      <?php }else{ ?>
        <td><?php echo $laporan->no_spm; ?></td>
      <?php } ?>
      <?php if ($laporan->jenis_surat == 'ls')  { ?>
       <td><span class="badge bg-blue"><?php echo strtoupper($laporan->jenis_surat); ?></span></td>
      <?php }else{ ?>
        <td><span class="badge bg-red"><?php echo strtoupper($laporan->jenis_surat); ?></span></td>
      <?php } ?>
      <td><?php echo $laporan->keterangan; ?></td>
      <td><?php echo $laporan->tgl_input; ?></td>
      <td class="text-center" style="min-width:230px;">
          <button class="btn btn-warning update-dataLaporan" data-id="<?php echo $laporan->id; ?>"><i class="glyphicon glyphicon-repeat"></i> Update</button>
          <button class="btn btn-danger konfirmasiHapus-laporan" data-id="<?php echo $laporan->id; ?>" data-toggle="modal" data-target="#konfirmasiHapus"><i class="glyphicon glyphicon-remove-sign"></i> Delete</button>
      </td>
    </tr>
    <?php
    $no++;
  }
?>