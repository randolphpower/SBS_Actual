<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul>
                <li class="text-muted menu-title">Navegaci&oacute;n</li>

                <li class="has_sub">
                    <a href="principal.php" class="waves-effect"><i class="zmdi zmdi-home"></i><span> Principal </span></a>
                </li>
                <?php if($_SESSION['rol']=='administrador'){ ?>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-users"></i>
                        <span> Usuarios </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                    	<li><a href="crear_usuario.php">Crear Usuario</a></li>
                    	<li><a href="actualizar_usuario.php">Consultar Usuarios</a></li>
                    </ul>    
                </li>
				<?php } ?>
                <li class="has_sub">
                    <a href="proceso_judicial.php" class="waves-effect"><i class="fa fa-legal"></i>
                        <span> Procesos Judiciales </span></a>
                </li>

				<li class="has_sub">
                    <a href="carga_asignaciones.php" class="waves-effect"><i class="zmdi zmdi-upload"></i>
                        <span> Carga Asignaciones </span>
                    </a>
                </li>
                
                <li class="has_sub">
                    <a href="carga_poder_judicial.php" class="waves-effect"><i class="zmdi zmdi-upload"></i>
                        <span> Carga Poder Judicial </span>
                    </a>
				</li>

                <li class="has_sub">
                    <a href="carga_datos.php" class="waves-effect"><i class="zmdi zmdi-upload"></i>
                        <span> Carga de Archivos </span>
                    </a>
				</li>
            	<!-- <li class="has_sub">
                    <a href="reporte_old.php" class="waves-effect"><i class="zmdi zmdi-file"></i>
                        <span> Reportes OLD</span>
                    </a>
                </li> -->
                <li class="has_sub">
                    <a href="reporte.php" class="waves-effect"><i class="zmdi zmdi-file"></i>
                        <span> Reportes </span>
                    </a>
				</li>

                <?php if($_SESSION['rol']=='administrador'){ ?>
                    <li class="has_sub">
                    <a href="eliminacion.php" class="waves-effect"><i class="zmdi zmdi-minus-circle-outline"></i>
                        <span> Eliminación de Registros </span>
                    </a>
                </li>
                <li class="has_sub">
                    <a href="eliminacion_masiva.php" class="waves-effect"><i class="zmdi zmdi-minus-circle"></i>
                        <span> Eliminación Masiva </span>
                    </a>
                </li>
                <li class="has_sub">
                    <a href="descargar_data.php" target="_parent" title="Descargar Data de Cliente Juicio" class="waves-effect"><i class="zmdi zmdi-download"></i>
                        <span> Descargar data </span>
                    </a>
				</li>
				<li class="has_sub">
                    <a href="visualizar_logs.php" target="_parent" title="Visualizar logs de carga" class="waves-effect"><i class="zmdi zmdi-file"></i>
                        <span> Visualizar Logs </span>
                    </a>
				</li>
                <?php } ?>

            <?php if (false) { ?>
 		
		           	
            	<li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-upload"></i>
                        <span> Custodia </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled">
                    	<li><a href="ingresar_doc_custodia.php">Carga de datos</a></li>
                    	<li><a href="comparar_arch_custodia.php">Por revisi&oacute;n</a></li>
                    	<li><a href="resumen_custodia.php">Resumen de Cargas</a></li>
						<li><a href="mis_custodia.php">Mis Custodias</a></li>
                    </ul>    
                </li>
                 
            <?php } ?>

            </ul>
            <div class="clearfix"></div>
        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>

</div>
<!-- Left Sidebar End -->
