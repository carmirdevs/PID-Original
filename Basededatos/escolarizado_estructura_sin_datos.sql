-- PID original: esquema estructural sin registros institucionales.
-- Generado a partir del respaldo suministrado; no incluye datos.
SET NAMES utf8mb4;

CREATE TABLE `archivos` (
  `id` int(11) NOT NULL,
  `nombre_archivo` varchar(255) DEFAULT NULL,
  `tipo_archivo` varchar(255) DEFAULT NULL,
  `instrumentacion_id` int(11) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT NULL,
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE `bloques` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'inactivo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE `carreras` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'inactivo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=COMPACT;

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `descripcion_grupo` varchar(200) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'inactivo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=COMPACT;

CREATE TABLE `gruposalumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'inactivo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE `imprimir` (
  `nomcarrera` varchar(250) NOT NULL,
  `nommateria` varchar(250) NOT NULL,
  `numdocente` varchar(250) NOT NULL,
  `nomdocente` varchar(250) NOT NULL,
  `apepdocente` varchar(250) NOT NULL,
  `apemdocente` varchar(250) NOT NULL,
  `fechaplan` varchar(100) NOT NULL,
  `numbloque` varchar(100) NOT NULL,
  `numgrupo` varchar(100) NOT NULL,
  `reporte1` varchar(100) NOT NULL,
  `reporte2` varchar(100) NOT NULL,
  `reporte3` varchar(100) NOT NULL,
  `reporte4` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `instrumentaciones` (
  `id` int(11) NOT NULL,
  `carrera_id` int(11) DEFAULT NULL,
  `materia_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `periodo_id` int(11) DEFAULT NULL,
  `grupoalumnos_id` int(11) DEFAULT NULL,
  `bloque_id` int(11) DEFAULT NULL,
  `fecha_reporte1` varchar(255) DEFAULT 'no entregada',
  `fecha_reporte2` varchar(255) DEFAULT 'no entregada',
  `fecha_reporte3` varchar(255) DEFAULT 'no entregada',
  `fecha_reporte4` varchar(255) DEFAULT 'no entregada',
  `fecha_instrumentacion` varchar(255) DEFAULT 'no entregada',
  `fecha_modificacion` varchar(255) DEFAULT 'no aplica',
  `numero_modificaciones` int(11) DEFAULT 0,
  `status` varchar(255) DEFAULT 'activo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=REDUNDANT;

CREATE TABLE `materias` (
  `id` int(11) NOT NULL,
  `clave` varchar(200) DEFAULT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'inactivo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=COMPACT;

CREATE TABLE `materia_carrera` (
  `id` int(11) NOT NULL,
  `materia_id` int(11) DEFAULT NULL,
  `carrera_id` int(11) DEFAULT NULL,
  `status` varchar(200) DEFAULT 'inactivo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=COMPACT;

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `descripcion_menu` varchar(200) DEFAULT NULL,
  `label_menu` varchar(255) DEFAULT NULL,
  `icon_menu` varchar(200) DEFAULT NULL,
  `status` varchar(200) DEFAULT 'inactivo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=COMPACT;

CREATE TABLE `metodos` (
  `id` int(11) NOT NULL,
  `descripcion_metodo` varchar(200) DEFAULT NULL,
  `label_metodo` varchar(255) DEFAULT NULL,
  `label_accion` varchar(255) DEFAULT NULL,
  `icon_accion` varchar(200) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'activo',
  `status_menu` varchar(200) DEFAULT 'inactivo',
  `status_accion` varchar(200) DEFAULT 'inactivo',
  `menu_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `usuario_alta_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=REDUNDANT;

CREATE TABLE `metodo_grupo` (
  `id` int(11) NOT NULL,
  `metodo_id` int(11) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'activo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=REDUNDANT;

CREATE TABLE `periodos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'inactivo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=COMPACT;

CREATE TABLE `sessiones` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=COMPACT;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `user` varchar(200) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido_p` varchar(255) DEFAULT NULL,
  `apellido_m` varchar(255) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `status` varchar(11) DEFAULT 'inactivo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=COMPACT;

CREATE TABLE `usuario_carrera` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `carrera_id` int(11) DEFAULT NULL,
  `status` varchar(200) DEFAULT 'inactivo',
  `usuario_alta_id` int(11) DEFAULT NULL,
  `usuario_update_id` int(11) DEFAULT NULL,
  `fecha_alta` timestamp NULL DEFAULT current_timestamp(),
  `fecha_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=COMPACT;

ALTER TABLE `archivos`
  ADD PRIMARY KEY (`id`) USING BTREE;

ALTER TABLE `bloques`
  ADD PRIMARY KEY (`id`) USING BTREE;

ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE,
  ADD UNIQUE KEY `descripcion` (`nombre`) USING BTREE,
  ADD KEY `usuario_alta_id` (`usuario_alta_id`) USING BTREE,
  ADD KEY `usuario_update_id` (`usuario_update_id`) USING BTREE;

ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE,
  ADD UNIQUE KEY `descripcion` (`descripcion_grupo`) USING BTREE,
  ADD KEY `grupo_ibfk_1` (`usuario_alta_id`) USING BTREE,
  ADD KEY `usuario_update_id` (`usuario_update_id`) USING BTREE;

ALTER TABLE `gruposalumnos`
  ADD PRIMARY KEY (`id`) USING BTREE;

ALTER TABLE `instrumentaciones`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `planeacion` (`carrera_id`,`materia_id`,`usuario_id`,`periodo_id`,`grupoalumnos_id`,`bloque_id`) USING BTREE,
  ADD KEY `metodo_grupo_ibfk_3` (`usuario_alta_id`) USING BTREE,
  ADD KEY `metodo_grupo_ibfk_4` (`usuario_update_id`) USING BTREE,
  ADD KEY `materia_id` (`materia_id`) USING BTREE,
  ADD KEY `usuario_id` (`usuario_id`) USING BTREE,
  ADD KEY `periodo_id` (`periodo_id`) USING BTREE;

ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE,
  ADD UNIQUE KEY `clave` (`clave`) USING BTREE,
  ADD KEY `usuario_alta_id` (`usuario_alta_id`) USING BTREE,
  ADD KEY `usuario_update_id` (`usuario_update_id`) USING BTREE,
  ADD KEY `descripcion` (`nombre`) USING BTREE;

ALTER TABLE `materia_carrera`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `materia_id` (`materia_id`,`carrera_id`) USING BTREE,
  ADD KEY `usuario_alta_id` (`usuario_alta_id`) USING BTREE,
  ADD KEY `usuario_update_id` (`usuario_update_id`) USING BTREE,
  ADD KEY `usuario_id` (`materia_id`) USING BTREE,
  ADD KEY `carrera_id` (`carrera_id`) USING BTREE;

ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `descripcion_menu` (`descripcion_menu`) USING BTREE,
  ADD KEY `usuario_alta_id` (`usuario_alta_id`) USING BTREE,
  ADD KEY `usuario_update_id` (`usuario_update_id`) USING BTREE;

ALTER TABLE `metodos`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE,
  ADD UNIQUE KEY `descripcion` (`descripcion_metodo`,`menu_id`) USING BTREE,
  ADD KEY `metodos_ibfk_1` (`menu_id`) USING BTREE,
  ADD KEY `metodos_ibfk_2` (`usuario_update_id`) USING BTREE,
  ADD KEY `metodos_ibfk_3` (`usuario_alta_id`) USING BTREE;

ALTER TABLE `metodo_grupo`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `metodo_grupo_ibfk_1` (`metodo_id`) USING BTREE,
  ADD KEY `metodo_grupo_ibfk_2` (`grupo_id`) USING BTREE,
  ADD KEY `metodo_grupo_ibfk_3` (`usuario_alta_id`) USING BTREE,
  ADD KEY `metodo_grupo_ibfk_4` (`usuario_update_id`) USING BTREE;

ALTER TABLE `periodos`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE,
  ADD UNIQUE KEY `descripcion` (`nombre`) USING BTREE,
  ADD KEY `usuario_alta_id` (`usuario_alta_id`) USING BTREE,
  ADD KEY `usuario_update_id` (`usuario_update_id`) USING BTREE;

ALTER TABLE `sessiones`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `grupo_id` (`grupo_id`) USING BTREE;

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `user` (`user`) USING BTREE,
  ADD UNIQUE KEY `nombre` (`nombre`,`apellido_p`,`apellido_m`) USING BTREE,
  ADD KEY `grupo_id` (`grupo_id`) USING BTREE,
  ADD KEY `usuario_alta_id` (`usuario_alta_id`) USING BTREE,
  ADD KEY `usuario_update_id` (`usuario_update_id`) USING BTREE;

ALTER TABLE `usuario_carrera`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`carrera_id`) USING BTREE,
  ADD KEY `usuario_alta_id` (`usuario_alta_id`) USING BTREE,
  ADD KEY `usuario_update_id` (`usuario_update_id`) USING BTREE,
  ADD KEY `carrera_id` (`carrera_id`) USING BTREE;

ALTER TABLE `archivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18851;

ALTER TABLE `bloques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `carreras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `gruposalumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=399;

ALTER TABLE `instrumentaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6424;

ALTER TABLE `materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=906;

ALTER TABLE `materia_carrera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2498;

ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

ALTER TABLE `metodos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

ALTER TABLE `metodo_grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=507;

ALTER TABLE `periodos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `sessiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19319;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=292;

ALTER TABLE `usuario_carrera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1719;

ALTER TABLE `carreras`
  ADD CONSTRAINT `carreras_ibfk_1` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `carreras_ibfk_2` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `grupos`
  ADD CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `grupos_ibfk_2` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `instrumentaciones`
  ADD CONSTRAINT `instrumentaciones_ibfk_3` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `instrumentaciones_ibfk_4` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `instrumentaciones_ibfk_5` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `instrumentaciones_ibfk_6` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `instrumentaciones_ibfk_7` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `instrumentaciones_ibfk_8` FOREIGN KEY (`periodo_id`) REFERENCES `periodos` (`id`) ON UPDATE CASCADE;

ALTER TABLE `materias`
  ADD CONSTRAINT `materias_ibfk_1` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `materias_ibfk_2` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `materia_carrera`
  ADD CONSTRAINT `materia_carrera_ibfk_1` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `materia_carrera_ibfk_2` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `materia_carrera_ibfk_3` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `materia_carrera_ibfk_4` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `menus_ibfk_2` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `metodos`
  ADD CONSTRAINT `metodos_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `metodos_ibfk_2` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `metodos_ibfk_3` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `metodo_grupo`
  ADD CONSTRAINT `metodo_grupo_ibfk_1` FOREIGN KEY (`metodo_id`) REFERENCES `metodos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `metodo_grupo_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `metodo_grupo_ibfk_3` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `metodo_grupo_ibfk_4` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `periodos`
  ADD CONSTRAINT `periodos_ibfk_1` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `periodos_ibfk_2` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `sessiones`
  ADD CONSTRAINT `sessiones_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

ALTER TABLE `usuario_carrera`
  ADD CONSTRAINT `usuario_carrera_ibfk_1` FOREIGN KEY (`usuario_alta_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_carrera_ibfk_2` FOREIGN KEY (`usuario_update_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_carrera_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_carrera_ibfk_4` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
