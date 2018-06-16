#!/bin/sh
# Workbench Table Data copy script
# Workbench Version: 6.3.9
# 
# Execute this to copy table data from a source RDBMS to MySQL.
# Edit the options below to customize it. You will need to provide passwords, at least.
# 
# Source DB: Mysql@127.0.0.1:3306 (MySQL)
# Target DB: Mysql@127.0.0.1:3306


# Source and target DB passwords
arg_source_password=
arg_target_password=

if [ -z "$arg_source_password" ] && [ -z "$arg_target_password" ] ; then
    echo WARNING: Both source and target RDBMSes passwords are empty. You should edit this file to set them.
fi
arg_worker_count=2
# Uncomment the following options according to your needs

# Whether target tables should be truncated before copy
# arg_truncate_target=--truncate-target
# Enable debugging output
# arg_debug_output=--log-level=debug3

/usr/bin/wbcopytables \
 --mysql-source="root@127.0.0.1:3306" \
 --target="root@127.0.0.1:3306" \
 --source-password="$arg_source_password" \
 --target-password="$arg_target_password" \
 --thread-count=$arg_worker_count \
 $arg_truncate_target \
 $arg_debug_output \
 --table '`dbVentas1`' '`invoice`' '`dbVentas`' '`invoice`' '`idinvoice`,`iddetalle_venta`' '`idinvoice`,`iddetalle_venta`' '`idinvoice`, `iddetalle_venta`, `total_venta`, `pago_efectivo`, `pago_tarjeta`, `vuelto`, `vaucher`' --table '`dbVentas1`' '`persona`' '`dbVentas`' '`persona`' '`idpersona`' '`idpersona`' '`idpersona`, `tipo_persona`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`' --table '`dbVentas1`' '`detalle_ingreso`' '`dbVentas`' '`detalle_ingreso`' '`iddetalle_ingreso`' '`iddetalle_ingreso`' '`iddetalle_ingreso`, `idingreso`, `idarticulo`, `cantidad`, `precio_compra`, `precio_venta`' --table '`dbVentas1`' '`detalle_venta`' '`dbVentas`' '`detalle_venta`' '`iddetalle_venta`' '`iddetalle_venta`' '`iddetalle_venta`, `idventa`, `idarticulo`, `cantidad`, `precio_venta`, `descuento`' --table '`dbVentas1`' '`password_resets`' '`dbVentas`' '`password_resets`' '-' '-' '`email`, `token`, `created_at`' --table '`dbVentas1`' '`migrations`' '`dbVentas`' '`migrations`' '-' '-' '`migration`, `batch`' --table '`dbVentas1`' '`categoria`' '`dbVentas`' '`categoria`' '`idcategoria`' '`idcategoria`' '`idcategoria`, `nombre`, `descripcion`, `condicion`' --table '`dbVentas1`' '`ingreso`' '`dbVentas`' '`ingreso`' '`idingreso`' '`idingreso`' '`idingreso`, `idproveedor`, `tipo_comprobante`, `serie_comprobante`, `num_comprobante`, `fecha_hora`, `impuesto`, `estado`' --table '`dbVentas1`' '`users`' '`dbVentas`' '`users`' '`id`' '`id`' '`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`' --table '`dbVentas1`' '`venta`' '`dbVentas`' '`venta`' '`idventa`' '`idventa`' '`idventa`, `idcliente`, `tipo_comprobante`, `serie_comprobante`, `num_comprobante`, `fecha_hora`, `impuesto`, `total_venta`, `estado`, `fecha`' --table '`dbVentas1`' '`articulo`' '`dbVentas`' '`articulo`' '`idarticulo`' '`idarticulo`' '`idarticulo`, `idcategoria`, `codigo`, `nombre`, `stock`, `descripcion`, `imagen`, `estado`'

