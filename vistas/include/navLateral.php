<section class="full-box nav-lateral">
	<div class="full-box nav-lateral-bg show-nav-lateral"></div>
	<div class="full-box nav-lateral-content">
		<figure class="full-box nav-lateral-avatar">
			<i class="far fa-times-circle show-nav-lateral"></i>
			<img src="<?php echo SERVERURL; ?>vistas/assets/avatar/Avatar.png" class="img-fluid" alt="Avatar">
			<figcaption class="roboto-medium text-center">
				<?php echo $_SESSION['nombres_auth']." ".$_SESSION['apellidos_auth']; ?> <br><small class="roboto-condensed-light"><?php if ($_SESSION['rol_auth'] == 1) { echo "Administrador"; } else if ($_SESSION['rol_auth'] == 2) { echo "Empleado"; } else { echo "Vendedor"; } ?></small>
			</figcaption>
		</figure>
		<div class="full-box nav-lateral-bar"></div>
		<nav class="full-box nav-lateral-menu">
			<ul>
				<li>
					<a href="<?php echo SERVERURL; ?>home/"><i class="fab fa-dashcube fa-fw"></i> &nbsp; Inicio</a>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-users fa-fw"></i> &nbsp; Clientes <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Cliente</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de clientes</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar cliente</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-truck fa-fw"></i> &nbsp; Proveedores <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>provider-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar proveedor</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>provider-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de proveedores</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>provider-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar proveedor</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-pallet fa-fw"></i> &nbsp; Items <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar item</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de items</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar item</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-prescription-bottle-alt fa-fw"></i> &nbsp; Medicinas <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>medicine-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar medicina</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>medicine-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de medicinas</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>medicine-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar medicina</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-briefcase-medical fa-fw"></i> &nbsp; Servicios <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>service-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar servicio</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>service-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de servicios</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>service-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar servicio</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-graduation-cap fa-fw"></i> &nbsp; Cursos <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>course-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar curso</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>course-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de cursos</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>course-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar curso</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; Ventas <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>reservation-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Registrar venta</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>reservation-pending/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de ventas</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>reservation-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar venta</a>
						</li>
					</ul>
				</li>

				<!--li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-chalkboard-teacher fa-fw"></i> &nbsp; Inscripciones <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href=""><i class="fas fa-plus fa-fw"></i> &nbsp; Registrar inscripción</a>
						</li>
						<li>
							<a href=""><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de inscripciones</a>
						</li>
						<li>
							<a href=""><i class="fas fa-search fa-fw"></i> &nbsp; Buscar inscripción</a>
						</li>
					</ul>
				</li-->

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-file-alt fa-fw"></i> &nbsp; Reportes <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>report/"><i class="fas fa-plus fa-fw"></i> &nbsp; Reportes</a>
						</li>
						<!--li>
							<a href=""><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Reporte de inscripciones</a>
						</li-->
					</ul>
				</li>
				<!--
				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-file-invoice-dollar fa-fw"></i> &nbsp; Préstamos <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="</?php echo SERVERURL; ?>reservation-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo préstamo</a>
						</li>
						<li>
							<a href="</?php echo SERVERURL; ?>reservation-reservation/"><i class="far fa-calendar-alt fa-fw"></i> &nbsp; Reservaciones</a>
						</li>
						<li>
							<a href="</?php echo SERVERURL; ?>reservation-pending/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; Préstamos</a>
						</li>
						<li>
							<a href="</?php echo SERVERURL; ?>reservation-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Finalizados</a>
						</li>
						<li>
							<a href="</?php echo SERVERURL; ?>reservation-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; Buscar por fecha</a>
						</li>
					</ul>
				</li>-->
				<?php if ($_SESSION['rol_auth'] == 1) { ?>
				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-address-book fa-fw"></i> &nbsp; Empleados <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo empleado</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de empleados</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar empleado</a>
						</li>
					</ul>
				</li>
				<?php } ?>
				<li>
					<!--<a href="<//?php echo SERVERURL; ?>company/"><i class="fas fa-store-alt fa-fw"></i> &nbsp; Empresa</a>-->
				</li>
			</ul>
		</nav>
	</div>
</section>