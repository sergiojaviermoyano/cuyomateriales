insert into sismenu VALUES (null, 'Actualizar Precios por Rubro', '', 'rubro', 'upgrate', '12');
insert into sismenuactions values (null, (Select menuId from sismenu where menuName = 'Actualizar Precios por Rubro'),(select actId from sisactions where actDescription = 'Edit'));
