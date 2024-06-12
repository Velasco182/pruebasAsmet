#INSERT INTO prueba.clientes(nombre, apellido, telefono) VALUES ('Ana', 'García', 5551234), ('Carlos', 'Pérez', 5555678), ('Elena', 'Rodríguez', 5558765), ('Luis', 'Martínez', 5554321), ('María', 'López', 5556789);
#INSERT INTO prueba.colaboradores(nombre_colab, identificacion_colab) VALUES ('Rubén Velasco', 1061804052);

UPDATE prueba.compensatorios SET 
            colaborador_id_compe = 51, 
            descripcion_compe = "Prueba DB Act", 
            inicio_compe = CONVERT(STR_TO_DATE("11/06/2024 01:00 PM", '%d/%m/%Y %l:%i %p'),DATETIME), 
            final_compe = CONVERT(STR_TO_DATE("12/06/2024 05:00 PM", '%d/%m/%Y %l:%i %p'), DATETIME), 
            validacion_compe = "Aceptado"  WHERE id_compe = 47;

            

/*INSERT INTO prueba.compensatorios (colaborador_id_compe, descripcion_compe, inicio_compe, final_compe, validacion_compe)
VALUES (51, "Prueba DB 2", 
CONVERT(STR_TO_DATE("12/06/2024 01:00 P. M.", '%d/%m/%Y %l:%i %p'),DATETIME),
CONVERT(STR_TO_DATE("11/06/2024 05:00 PM", '%d/%m/%Y %l:%i %p'), DATETIME), "Pendiente");*/
