<?php require_once(dirname(__FILE__) . "/escpos-php-master/Escpos.php");
              //$logo = new EscposImage("images/productos/tostao.jpg");

              $printer = new Escpos();
              /* Print top logo */
                            $printer -> setJustification(Escpos::JUSTIFY_CENTER);
                            
                             $printer ->  setEmphasis(true);
                             $printer -> text("FACTURA \n");
                             $printer -> text(" \n");
                             $printer -> text("C - \n");
                             $printer -> text("ruc: \n");
                             $printer -> text("TICKET \n");
                             $printer -> text("001-000013\n");
                             $printer -> setEmphasis(false);
                             $printer -> feed();
                             $printer -> setJustification();
              $printer -> setFont(Escpos::FONT_C);
              $printer -> feed();
              $printer -> text("#CAJA:5       09-02-2016 19:12:52\n");
              $printer -> text("Ticket                  <original>\n");
              $printer -> text("-------------------------------------\n");$printer -> text("TIPO:Efec. DNI N°:\n");
              $printer -> text("Cliente: Fernando Peres\n");
              $printer -> feed();
              $printer -> text("Direccion: \n");
              $printer -> feed();
              $printer -> text("Cajero: admin\n");
              $printer -> text("Vendedor: .....\n");
              $printer -> text("-------------------------------------\n");
              $printer -> text("Descripcion \n");
              $printer -> text("Precio      cant           Total \n");
              $printer -> text("-------------------------------------\n");
              $printer -> text("Engranaje  27(SPUR GEAR, 27)(Engranaje  27(SPUR GEAR, 27)/ Mod:FX-890,FX-2190)\n");
                              
                              $printer -> text("10.00       1.00          10.00\n");
                              $printer -> text("Engranaje de combinacion(Engranaje de combinacion/ Mod:FX-890)\n");
                              
                              $printer -> text("10.00       1.00          10.00\n");
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("IGV(18%)               S/.3.05\n");                            
                              $printer -> text("Subtotal               S/.16.95\n");
                              
                              
                              $printer -> text("Pago adelantado(anticipo)    0.00\n");
                              $printer -> text("Vale de Consumo              0.00\n");
                              $printer -> text("descuento especial         S/.0\n");
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("Monto P. Tarjeta     S/.0.00\n"); 
                              $printer -> text("Monto P. Efectivo    S/.20.00\n"); 
                              $printer -> text("TOTAL                S/.20.00\n");
                              $printer -> feed(); 
                              $printer -> text("-------------------------------------\n");
                              $printer -> text("Importe Pagado             S/.20\n");
                              $printer -> text("Vuelto                 S/.0\n"); 
                              $printer -> text("-------------------------------------\n"); 
                              $printer -> text("-------------------------------------\n"); 
                              $printer -> setEmphasis(true);$printer -> text("-------------------------------------\n");$printer -> setEmphasis(true);$printer -> text("Comuniquense con nosotros al:\n");$printer -> text("\n");$printer -> setEmphasis(false);$printer -> feed();$printer -> feed();$printer -> cut();$printer -> pulse();$printer -> close();