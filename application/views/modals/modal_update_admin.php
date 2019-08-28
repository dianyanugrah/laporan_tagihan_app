<div class="col-md-offset-1 col-md-10 col-md-offset-1 well">
  <div class="form-msg"></div>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h3 style="display:block; text-align:center;">Update Data User</h3>

  <form id="form-update-user" method="POST">
    <div class="form-group">
      <label>Nama</label>
      <input type="text" class="form-control" placeholder="Nama User" name="nama" aria-describedby="sizing-addon2" value="<?php echo $dataUser->nama; ?>">
    </div>
    <div class="form-group">
      <label>Username </label>
      <input type="text" class="form-control" placeholder="Username" name="username" aria-describedby="sizing-addon2" value="<?php echo $dataUser->username; ?>">
    </div>
    <div class="form-group">
      <label>Level User</label>
      <select name="level" class="form-control">
        <option value="1" <?php if ($dataUser->level == '1') {echo "selected='selected'";} ?>>Super Admin</option>
         <option value="2" <?php if ($dataUser->level == '2') {echo "selected='selected'";} ?>>Admin</option>
         <option value="3" <?php if ($dataUser->level == '3') {echo "selected='selected'";} ?>>Staff</option>
      </select>
    </div>
    <div class="form-group">
      <div class="col-md-12">
          <button type="submit" class="form-control btn btn-primary"> <i class="glyphicon glyphicon-ok"></i> Ubah Data</button>
      </div>
    </div>
  </form>
</div>