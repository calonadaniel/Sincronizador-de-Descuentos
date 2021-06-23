<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\DescuentosGlobales_Articulos_Promocional;
use App\Models\DescuentosGlobales_DireccionesIP;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class promoSincronizarCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promoSincronizar:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza los descuentos promocionales';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Obtener lista de farmacias
        $direccionesIP = DescuentosGlobales_DireccionesIP::from('L@Red_Software_DescuentosGlobales_DireccionesIP as DirIP')
        ->select('store.Name','DirIP.DireccionIP','DirIP.BDD','DirIP.Usuario','DirIP.Contraseña')
        ->join('store as store','DirIP.storeID','=','store.ID')
        ->where('store.Name', 'not like', '%ZZ%' )
        ->where('store.Name', 'not like', '%ProNAF%' )
        ->where('store.Name', 'not like', '%Administracion%')
        ->where('store.Name', 'not like', '%Bodega%')
        ->where('store.Name', 'not like', '%Distribucion%')
        ->where('store.Name', 'not like', '%DevolucionesPro%')
        ->orderby('store.Name','asc')
        ->get();
        //->toSql(); Aqui me devuelve la consulta en raw

        $sinConexionArray = array(); //Lista de farmacias que no sincronizaron los descuentos

        $serverName =  "192.168.222.4"; //serverName\instanceName o la IP
        $connectionInfo = array("Database"=>"fcHQdb", "UID"=>"sa", "PWD"=>"sa2000", "LoginTimeout"=>5);
        $conexionHQ = sqlsrv_connect( $serverName, $connectionInfo);

        // Borrar descuentos promocionales en caso que existieran registros anteriores en el HQ
        $query = "delete L@Red_Software_DescuentosGlobales_Articulos_Promocional"; //Cuando se usa truncate a veces la sincronizacion se vuelve muy lenta
        sqlsrv_query($conexionHQ, $query);

        //Insertar descuentos por medio de comando SQL.

        //En este parte dependiendo del dia se selecciona que descuento aplicar por medio de una query SQL
        //Modificar la consulta sql en la variable $query en caso de querer cambiar a que farmacia aplicar o que productos incluir o excluir.
        $today = date("D"); //La funcion date me devuelve

        switch($today){
            case "Mon":
                break; //Si no se coloca el break a cada dia el programa no termina de ejecutarse loop infinito 
            case "Tue":
                //Martes saludables
                $query = "
                INSERT INTO [fchqdb].[dbo].[L@Red_Software_DescuentosGlobales_Articulos_Promocional]
                ([ItemLookupCode],[StoreID],[Discount],[DateCreated],[LastUpdated])
                Select ItemLookupCode,StoreID,Discount+10,GETDATE(),GETDATE() from dbo.L@Red_Software_DescuentosGlobales_Articulos
                Where ItemLookUpCode In ('7441151905067', '7441151905067H' 
                ,'7441151906637', '7441151906637H' 
                ,'7501033922909', '7501033922909H' 
                ,'7790440668306', '7790440668306H' 
                ,'07420037200977H', '07420037200977HH' 
                ,'07420037200977', '07420037200977H' 
                ,'8906052540902H', '8906052540902HH' 
                ,'8906052540902', '8906052540902H' 
                ,'7420037201066H', '7420037201066HH' 
                ,'7420037201066', '7420037201066H' 
                ,'138055651547', '138055651547H' 
                ,'7401133400065', '7401133400065H' 
                ,'7420001031194', '7420001031194H' 
                ,'974060963', '974060963H' 
                ,'9740601760', '9740601760H' 
                ,'9740602144', '9740602144H' 
                ,'5600360211594', '5600360211594H' 
                ,'9740601969H', '9740601969HH' 
                ,'974060582', '974060582H' 
                ,'974060458H', '974060458HH' 
                ,'974060458', '974060458H' 
                ,'7401021630048', '7401021630048H' 
                ,'7401021630055', '7401021630055H' 
                ,'7401021630031', '7401021630031H' 
                ,'7401021630024', '7401021630024H' 
                ,'7401021630017', '7401021630017H' 
                ,'7401021670020', '7401021670020H' 
                ,'717840002667', '717840002667H' 
                ,'717840003985', '717840003985H' 
                ,'717840006412', '717840006412H' 
                ,'717840005774', '717840005774H' 
                ,'717840005408', '717840005408H' 
                ,'9740601365', '9740601365H' 
                ,'9740601367', '9740601367H' 
                ,'9740601863', '9740601863H' 
                ,'711604100507', '711604100507H' 
                ,'711604102785', '711604102785H' 
                ,'765446472407', '765446472407H' 
                ,'7401010900244', '7401010900244H' 
                ,'9740601403', '9740601403H' 
                ,'7703763750191H', '7703763750191HH' 
                ,'850319000013', '850319000013H' 
                ,'7401018118542', '7401018118542H' 
                ,'7420002000564H', '7420002000564HH' 
                ,'7420002000564', '7420002000564H' 
                ,'7401094600832', '7401094600832H' 
                ,'7401094602812', '7401094602812H' 
                ,'9740601198', '9740601198H' 
                ,'7401094601563', '7401094601563H' 
                ,'7401094601570', '7401094601570H' 
                ,'7401092203615', '7401092203615H' 
                ,'7401092200317', '7401092200317H' 
                ,'7401092200416', '7401092200416H' 
                ,'7401092212518', '7401092212518H'   
                ,'74107742', '74107742H' 
                ,'9740601767', '9740601767H' 
                ,'7501108761839', '7501108761839H' 
                ,'9740601119', '9740601119H' 
                ,'9740601119H', '9740601119HH' 
                ,'9740601621', '9740601621H' 
                ,'974060899', '974060899H' 
                ,'7406137000051', '7406137000051H' 
                ,'7406137002260', '7406137002260H' 
                ,'7401104600913', '7401104600913H' 
                ,'7401104600920', '7401104600920H' 
                ,'7401104600456', '7401104600456H' 
                ,'7401104601057', '7401104601057H' 
                ,'7401104601262', '7401104601262H' 
                ,'7401104600777', '7401104600777H' 
                ,'702240449389', '702240449389H' 
                ,'7410001015270', '7410001015270H' 
                ,'7410001015287', '7410001015287H' 
                ,'764600210190', '764600210190H' 
                ,'764600110872H', '764600110872HH' 
                ,'764600110872', '764600110872H' 
                ,'764600110827', '764600110827H' 
                ,'764600122158', '764600122158H' 
                ,'764600123698', '764600123698H' 
                ,'7415100203788', '7415100203788H' 
                ,'7420075203107', '7420075203107H' 
                ,'7420075203121', '7420075203121H' 
                ,'7420075204487', '7420075204487H' 
                ,'7420075204166', '7420075204166H' 
                ,'7420075203183', '7420075203183H' 
                ,'7420075203176', '7420075203176H' 
                ,'7420075204333', '7420075204333H' 
                ,'7420075204548', '7420075204548H' 
                ,'7420075204494', '7420075204494H' 
                ,'7420075203060', '7420075203060H' 
                ,'7420075203077', '7420075203077H' 
                ,'7420075204180', '7420075204180H' 
                ,'7420075203145', '7420075203145H' 
                ,'7420075203138', '7420075203138H' 
                ,'7420075204517', '7420075204517H' 
                ,'7420075204364', '7420075204364H' 
                ,'7420075203039', '7420075203039H' 
                ,'7420075203046', '7420075203046H' 
                ,'7420075203053', '7420075203053H') ";
                break;
            case "Wed":
                // Miercoles Tercera promocional 10% adicional
                $query= "
                insert into L@Red_Software_DescuentosGlobales_Articulos_Promocional
                (
                ItemLookupCode,
                StoreID,
                Discount,
                DateCreated,
                LastUpdated
                )
                select 
                d3e.ItemLookupCode,
                d3e.StoreID,
                d3e.Discount+10,
                getdate(),
                getdate()
                from L@Red_Software_DescuentosGlobales_Articulos_3raEdad d3e
                left join Item on d3e.ItemLookupCode=item.itemlookupcode
                left join Department on item.DepartmentID=department.id
                where d3e.discount>0 and d3e.StoreID not in (105,110,69,63,103,44)
                and department.name not in ('3m insumo', ---
                'ABBOTT LECHES SIMAN','ABBOTT LECHES  PROCONSUMO','ASPEN LECHES', 'andifar leches','MEAD JOHNSON LECHES', 'finlay leches','NESTLE', ----todas las leches,
                'abbott sueros', ---
                'alfamedic', ---
                'ashonplafa', ---
                'babe laboratorios', ---
                'bayer otc', ---
                'bayer woman', ---
                'bioseguridad', ---
                'cantabria', ---
                'codis otc', ---
                'coinsa', ---
                'colgate', ---
                'compras locales', ---
                'curadol', ---
                'dicosa', ---
                'durex', ---
                'eucerin', ---
                'euromed', ---
                'galderma', ---
                'gallo', ---
                'genomma lab', ---
                'gsk otc', ---
                'heros', ---
                'herdz', ---
                'hospitalario-dicosa', ---
                'hospitalario-drog.americana', --
                'hospitalario-dromeinter', ---
                'hospitalario-farinter', ---
                'hospitalario-agencia matamoros', ---
                'johnson & johnson', ---
                'johnson&johnson farma', ---
                'kin lab', ---
                'lever', ---
                'mentholatum lab', ---
                'nateen', ---
                'neostrata', ---
                'nipro', ---
                'nivea', ---
                'off', ---
                'oral-b', ---
                'owen mumford', ---
                'p&g', ---
                'pasmo', ---
                'pharma internacional', ---
                'productos varios', ---
                'robis', ---
                'sebamed', ---
                'sesderma', ---
                'superior', ---
                'tecnoquimicas', ---
                'unilever', ---
                'uriage', ---
                'vallamer',
                'valle', ---
                'villasa', ---
                'zepol lab', --
                'aqua spring') ";
                break;
            case "Thu":
                break;
            case "Fri":
                break;
            case "Sat":
                //Sabados infantiles
                $query = "
                Insert into L@Red_Software_DescuentosGlobales_Articulos_Promocional
                ([ItemLookupCode],[StoreID],[Discount],[DateCreated],[LastUpdated])
                SELECT D.ItemLookupCode,D.[StoreID],Item.MSRP,getdate(),getdate()
                FROM [L@Red_Software_DescuentosGlobales_Articulos] D
                Left join Item on D.ItemLookupCode=item.itemlookupcode
                Where Item.MSRP<>0 ";
                break;
            case "Sun":
                break;
            default:
                break;
        }

        sqlsrv_query($conexionHQ, $query);
        //sqlsrv_close($conexionHQ);

        //Sincronizar los descuentos a las farmacias 
        foreach($direccionesIP as $serverStore) { 

			$serverName =  $serverStore->DireccionIP; //serverName\instanceName o la IP
			$connectionInfo = array("Database"=>$serverStore->BDD, "UID"=>$serverStore->Usuario, "PWD"=>$serverStore->Contraseña, "LoginTimeout"=>5);//, "ConnectionPooling"=>0,,"MultipleActiveResultSets"=>0);
            $conexionStore = sqlsrv_connect($serverName, $connectionInfo);
		
            if($conexionStore == true) {

                try {

                    //Comparar cantidad de descuentos que hay en el Store con los del HQ correspondientes a dicha Store
                    $querycantidadHQ = sqlsrv_query($conexionStore, "select count(dpHQ.itemlookupcode) cantidadHQ from opendatasource('sqloledb', 'server=192.168.222.4; user id=sa;password=sa2000').fchqdb.dbo.L@Red_Software_DescuentosGlobales_Articulos_Promocional dpHQ where dpHQ.storeid = (select storeid from configuration)");
                    $querycantidadStore = sqlsrv_query($conexionStore, "select count(itemlookupcode) cantidadStore from L@Red_Software_DescuentosGlobales_Articulos_Promocional");

                    $cantidadHQ;
                    $cantidadStore;

                    //Validar que no hubo error en los queries, caso contrario saltar sincronizacion
                    if($querycantidadHQ != false && $querycantidadStore != false) {

                        while ($rows = sqlsrv_fetch_array($querycantidadHQ, SQLSRV_FETCH_ASSOC)) {
                            $cantidadHQ = $rows['cantidadHQ'];
                        }
    
                        while ($rows = sqlsrv_fetch_array($querycantidadStore, SQLSRV_FETCH_ASSOC)) {
                            $cantidadStore = $rows['cantidadStore'];
                        }

                        //Si las cantidades difieren entre los del Store y los del HQ correspondientes al store insertar descuentos 
                        //caso contrario continuar con la siguiente farmacia
                        if ($cantidadHQ != $cantidadStore) {

                            //Borrar descuentos promocionales en caso que existieran de un dia anterior o estuviesen incompletos.
                            $query = "delete L@Red_Software_DescuentosGlobales_Articulos_Promocional"; //Cuando se usa truncate a veces la sincronizacion se vuelve muy lenta
                            $resultadoQuery = sqlsrv_query($conexionStore, $query);

                            if ($resultadoQuery != false) {

                                $query = "
                                insert into L@Red_Software_DescuentosGlobales_Articulos_Promocional
                                (
                                ItemLookupCode,
                                StoreID,
                                Discount,
                                DateCreated,
                                LastUpdated
                                )
                                select 
                                dpHQ.ItemLookupCode,
                                dpHQ.StoreID,
                                dpHQ.Discount,
                                dpHQ.DateCreated,
                                dpHQ.LastUpdated
                                from opendatasource('sqloledb', 'server=192.168.222.4; user id=sa;password=sa2000').fchqdb.dbo.L@Red_Software_DescuentosGlobales_Articulos_Promocional dpHQ
                                where dpHQ.storeid = (select storeid from configuration)";


                                $resultadoQuery = sqlsrv_query($conexionStore, $query);

                                if($resultadoQuery != false) {

                                    //Sincronizacion exitosa, cerrar conexion al Store
                                    //sqlsrv_close($conexionStore); 

                                } else {

                                    //Sincronizacion incompleta o se perdio conexion
                                    //Agregar al LOG de sin conexion
                                    $sinConexionArray[] = $serverStore->Name;
                                    //Tratar de cerrar la conexion de ser posible
                                    //sqlsrv_close($conexionStore); 
                                }
                                
                            } else {

                                $sinConexionArray[] = $serverStore->Name;
                                //sqlsrv_close($conexionStore);
                            }
                            
                        }else {

                            //Cerrar conexion con la store, los descuentos se encuentran completos
                            //sqlsrv_close($conexionStore); 

                        }

                    } else {

                        $sinConexionArray[] = $serverStore->Name;
                        //sqlsrv_close($conexionStore); 
                        
                    }

                } catch (\Exception $e) {
                    Log::channel('sinConexion')->info(json_encode($e)); 
                    //Log::channel('sinConexion')->info($e); 
                }
    
            }else {
                //Registrar farmacias que no sincronizaron 
                $sinConexionArray[] = $serverStore->Name;
                //sqlsrv_close($conexionStore); 
                //Log::channel('sinConexion')->info(json_encode($sinConexionArray)); 
            }

        }
        //Luego del foreach y que intentara realizar conexion con todas las farmacias cerrar conexion con el HQ
        //sqlsrv_close($conexionHQ);
        
        //Archivo Log con las farmacias que no sincronizaron o no se pudo eliminar el descuento
        Log::channel('sinConexion')->info(json_encode($sinConexionArray)); 
    }
}
