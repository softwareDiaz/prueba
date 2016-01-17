<?php require_once(dirname(__FILE__) . "/escpos-php-master/Escpos.php");
              //$logo = new EscposImage("images/productos/tostao.jpg");

              $printer = new Escpos();
              /* Print top logo */
                            $printer -> setJustification(Escpos::JUSTIFY_CENTER);
                            
                             $printer ->  setEmphasis(true);
                             $printer -> text("FACTURA \n");
                             $printer -> text("Sucursal E.I.R.L \n");
                             $printer -> text("CAv. Josel #2584 Chiclayo-Lambayeque \n");
                             $printer -> text("ruc:12356895648 \n");
                             $printer -> text("TICKET \n");
                             $printer -> text("001-000005\n");
                             $printer -> setEmphasis(false);
                             $printer -> feed();
                             $printer -> setJustification();
              $printer -> setFont(Escpos::FONT_C);
              $printer -> feed();
              $printer -> text("#CAJA:1       05-01-2016 13:11:02\n");
              $printer -> text("Ticket                  <original>\n");
              $printer -> text("-------------------------------------\n");$printer -> text("TIPO:Efec. RUC N°:12356895648\n");
              $printer -> text("Cliente: Empresa Cliente Ejemplo\n");
              $printer -> feed();
              $printer -> text("Direccion: Dirección Fiscal Ejemplo\n");
              $printer -> feed();
              $printer -> text("Cajero: soporte\n");
              $printer -> text("Vendedor: Empleado Ejemplo\n");
              $printer -> text("-------------------------------------\n");
              $printer -> text("Descripcion \n");
              $printer -> text("Precio      cant           Total \n");
              $printer -> text("-------------------------------------\n");
              $printer -> text("Celular(Celular/ CT:100MG /SB:Fresa)\n");
                              
                              $printer -> text("264.00       3.00          792.00\n");
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("IGV(18%)               S/.120.81\n");                            
                              $printer -> text("Subtotal               S/.671.19\n");
                              
                              
                              $printer -> text("Pago adelantado(anticipo)    0.00\n");
                              $printer -> text("Vale de Consumo              0.00\n");
                              $printer -> text("descuento especial         S/.0.00\n");
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("Monto P. Tarjeta     S/.0.00\n"); 
                              $printer -> text("Monto P. Efectivo    S/.792.00\n"); 
                              $printer -> text("TOTAL                S/.792.00\n");
                              $printer -> feed(); 
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("Importe Pagado             S/.800\n");
                              $printer -> text("Vuelto                 S/.8.00\n"); 
                              $printer -> text("-------------------------------------\n"); 
                              $printer -> text("-------------------------------------\n"); 
                              $printer -> setEmphasis(true);$printer -> text("-------------------------------------\n");$printer -> setEmphasis(true);$printer -> text("Comuniquense con nosotros al:\n");$printer -> text("\n");$printer -> setEmphasis(false);$printer -> feed();$printer -> feed();$printer -> cut();$printer -> pulse();$printer -> close();