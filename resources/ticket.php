<?php require_once(dirname(__FILE__) . "/escpos-php-master/Escpos.php");
              //$logo = new EscposImage("images/productos/tostao.jpg");

              $printer = new Escpos();
              /* Print top logo */
                            $printer -> setJustification(Escpos::JUSTIFY_CENTER);
                            
                             $printer ->  setEmphasis(true);
                             $printer -> text("FACTURA \n");
                             $printer -> text("Computel Peru S.A.C. \n");
                             $printer -> text("Csan Jose N° 1122 Chiclayo-Lambayeque \n");
                             $printer -> text("ruc: \n");
                             $printer -> text("TICKET \n");
                             $printer -> text("001-000040\n");
                             $printer -> setEmphasis(false);
                             $printer -> feed();
                             $printer -> setJustification();
              $printer -> setFont(Escpos::FONT_C);
              $printer -> feed();
              $printer -> text("#CAJA:16       20-02-2016 11:12:01\n");
              $printer -> text("Ticket                  <original>\n");
              $printer -> text("-------------------------------------\n");$printer -> text("TIPO:Efec. DNI N°:\n");
              $printer -> text("Cliente: BARTOLOME CURO\n");
              $printer -> feed();
              $printer -> text("Direccion: \n");
              $printer -> feed();
              $printer -> text("Cajero: admin\n");
              $printer -> text("Vendedor: .....\n");
              $printer -> text("-------------------------------------\n");
              $printer -> text("Descripcion \n");
              $printer -> text("Precio      cant           Total \n");
              $printer -> text("-------------------------------------\n");
              $printer -> text("Mantenimiento general\n");
                              
                              $printer -> text("60.00       1.00          60.00\n");
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("IGV(18%)               S/.9.15\n");                            
                              $printer -> text("Subtotal               S/.50.85\n");
                              
                              
                              $printer -> text("Pago adelantado(anticipo)    0.00\n");
                              $printer -> text("Vale de Consumo              0.00\n");
                              $printer -> text("descuento especial         S/.0\n");
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("Monto P. Tarjeta     S/.0.00\n"); 
                              $printer -> text("Monto P. Efectivo    S/.40.00\n"); 
                              $printer -> text("TOTAL                S/.60.00\n");
                              $printer -> feed(); 
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("Importe Pagado             S/.60\n");
                              $printer -> text("Vuelto                 S/.0\n"); 
                              $printer -> text("-------------------------------------\n"); 
                              $printer -> text("-------------------------------------\n"); 
                              $printer -> setEmphasis(true);$printer -> text("-------------------------------------\n");$printer -> setEmphasis(true);$printer -> text("Comuniquense con nosotros al:\n");$printer -> text("ventas@vomputelperu.com.pe\n");$printer -> setEmphasis(false);$printer -> feed();$printer -> feed();$printer -> cut();$printer -> pulse();$printer -> close();