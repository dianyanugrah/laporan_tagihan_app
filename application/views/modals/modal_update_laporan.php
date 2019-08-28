<div class="col-md-offset-1 col-md-10 col-md-offset-1 well">
  <div class="form-msg"></div>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h3 style="display:block; text-align:center;">Update Data Tagihan</h3>

  <form id="form-update-laporan" method="POST">
  <input type="hidden" name="id" value="<?php echo $dataLaporan->id; ?>">
    <div class="form-group">
      <label>No PPK</label>
      <input type="text" class="form-control" placeholder="Nomor PPK" name="no_ppk" aria-describedby="sizing-addon2" value="<?php echo $dataLaporan->no_ppk; ?>">
    </div>
    <div class="form-group">
      <label>No Agenda</label>
      <input type="text" class="form-control" placeholder="Nomor Agenda" name="no_agenda" aria-describedby="sizing-addon2" value="<?php echo $dataLaporan->no_agenda; ?>">
    </div>

    <div class="form-group">
      <label>Jenis Tagihan</label>
      <div class="row">
        <div class="col-lg-6">
          <div class="input-group">
                <span class="input-group-addon">
                 <input type="radio" name="jenis_surat" <?php if($dataLaporan->jenis_surat=="ls") {echo "checked";}?> value="ls">
                </span>
            <input type="text" class="form-control" value="LS" disabled="">
          </div>
          <!-- /input-group -->
        </div>
        <!-- /.col-lg-6 -->
        <div class="col-lg-6">
          <div class="input-group">
                <span class="input-group-addon">
                  <input type="radio" name="jenis_surat" <?php if($dataLaporan->jenis_surat=="up") {echo "checked";}?> value="up">
                </span>
            <input type="text" class="form-control" value="UP" disabled="">
          </div>
          <!-- /input-group -->
        </div>
        <!-- /.col-lg-6 -->
      </div>
    </div>
    <div class="form-group">
      <label>No SPM</label>
        <input type="text"  class="form-control spm" placeholder="Nomor SPM" name="no_spm" aria-describedby="sizing-addon2" <?php if ($dataLaporan->no_spm == '') { ?> value="-" disabled="" <?php } else {?> value="<?php echo $dataLaporan->no_spm; ?>" <?php } ?>>
    </div>
    <div class="form-group">
      <label>Keterangan</label>
      <textarea class="form-control" rows="3" name="keterangan" placeholder="Keterangan Tagihan" ><?php echo $dataLaporan->keterangan; ?></textarea>
    </div>
    <div class="form-group">
      <div class="col-md-12">
          <button type="submit" class="form-control btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Update Data</button>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
  $(function() {
    $('input[name="jenis_surat"]').on('click', function() {
        if ($(this).val() == 'ls') {
            $("input.spm").prop('disabled', false);
        }
        else {
            $("input.spm").prop('disabled', true);
        }
    });
});
</script>