/* 
En el respaldo de la base de datos ya se han implementado y creado las modificaciones que se mencionan a continuación.
*/

-- CAMBIAR TIPO DE DATOS A TOTAL_PAGADO
alter table pago_prestamo
alter column total_pagado TYPE numeric(14,2) USING total_pagado::numeric;

-- CAMBIAR TIPO DE DATO A SALDO_CUOTA
alter table cuota_prestamo
alter column saldo_cuota set data type numeric(10,2) using saldo_cuota::numeric;

-- drop function f_listar_pagos(date,date,integer);
create or replace function f_listar_pagos(date, date, integer) returns
table(numero_pago integer, fecha_pago date, nombecliente varchar, numero_prestamo integer, fecha_prestamo date, totalpagado numeric, estado varchar)
as
$$
DECLARE
	p_fecha1 alias for $1;
	p_fecha2 alias for $2;
	p_tipo alias for $3;
BEGIN
	RETURN QUERY
	SELECT
	pp.numero_pago, pp.fecha_pago, (c.nombres||' '||c.apellidos)::varchar as nombrecliente, ppc.numero_prestamo, p.fecha_prestamo, sum(ppc.importe_pagado),
	(case when pp.estado = 'E' then 'EMITIDO' else 'ANULADO' end)::varchar as estado
	FROM
	pago_prestamo pp 
	left join pago_prestamo_cuota ppc on pp.numero_pago=ppc.numero_pago
	inner join prestamo p on p.numero_prestamo=ppc.numero_prestamo
	inner join cliente c on c.dni_cliente=p.dni_cliente
	WHERE 
	(
		case p_tipo
		when 1 then pp.fecha_pago = current_date
		when 2 then pp.fecha_pago between p_fecha1 and p_fecha2
		else true
		end
	)
	GROUP BY
	pp.numero_pago, ppc.numero_prestamo, c.nombres, c.apellidos, p.fecha_prestamo;
END;
$$
language 'plpgsql';