<?php
  foreach ($dataAdmin as $admin) {
    ?>
    <tr>
      <td style="min-width:230px;"><?php echo $admin->username; ?></td>
      <td><?php echo $admin->nama; ?></td>
      <td><?php switch ($admin->level) {
          case '1':
          echo "Super Admin";
          break;
          case '2':
          echo "Admin";
          break;
          case '3':
          echo "Staff";
          break;
        default:
          echo "N/A";
          break;
      }?></td>
      <td class="text-center" style="min-width:230px;">
        <button class="btn btn-warning update-dataUser" data-id="<?php echo $admin->id; ?>"><i class="glyphicon glyphicon-repeat"></i> Update</button>
        <button class="btn btn-danger konfirmasiHapus-user" data-id="<?php echo $admin->id; ?>" data-toggle="modal" data-target="#konfirmasiHapus"><i class="glyphicon glyphicon-remove-sign"></i> Delete</button>
      </td>
    </tr>
    <?php
  }
?>
