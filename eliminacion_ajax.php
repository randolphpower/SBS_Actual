<?php

include("modelo/conectarBD.php");
include("modelo/consultaSQL.php");

if ($_SESSION["rol"] == "administrador") { 

   $rut = $_POST['rut'];
   
   $sql = "SELECT * FROM relacion_cliente_juicio ";
   $sql .= "WHERE ID_CLIENTE = '{$rut}';";
   $data = call_select($sql, "");
   
   if ($data['num_registros'] == 0) {

      echo "<h4>No se encontraron juicios asociados al rut ingresado</h4>";

   } else { 

   ?>
   <div class="row" style="background-color: white; margin-left: 10px; padding: 20px;">

      <strong>Juicios Asociados</strong>
      <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 12px">
      <thead>
      <tr>
         <th bgcolor="#5B9BD5">Rut</th>
         <th bgcolor="#5B9BD5">Nro Juicio</th>
         <th bgcolor="#5B9BD5">Accion</th>
      </tr>
      </thead>
      <tbody>
      <?php

      while ($results = mysql_fetch_array($data['registros'])) {   
      ?>
      <tr>
         <td><?php echo $results["ID_CLIENTE"] ?></td>
         <td><?php echo $results["NUM_JUICIO"] ?></td>
         <td>
            <button type="button" class="btn btn-danger btn-sm">eliminar</button>
         </td>
      </tr>
      <?php
      }

      ?>
      </tbody>
      </table>
      <?php
   }

   ?>




   </div>
   <?php

}

?>
   