
<?php

/**
 * List of Override Prices
 *
 */

require_once 'modules/admin/models/Package.php';
require_once 'modules/clients/models/UserPackage.php';
require_once 'modules/billing/models/Currency.php';
require_once 'modules/clients/models/User.php';
require_once 'modules/billing/models/Invoice.php';


class Override_Prices extends Report
{

    private $lang;

    protected $featureSet = 'support';

    /**
     * __construct
     *
     * @param  mixed $user
     * @param  mixed $customer
     * @return void
     */
    public function __construct($user = null, $customer = null)
    {
        $this->lang = lang('Override Prices');
        parent::__construct($user, $customer);
    }

    /**
     * process
     *
     * @return void
     */
    public function process()
    {
     
		
		$sql = "SELECT
        domains.CustomerID , 
        domains.dateActivated AS DateActivated, 
        domains.`status` AS `Status`, 
        domains.id AS OrderID, 
        domains.custom_price AS CustomPrice, 
        recurringfee.paymentterm, 
        recurringfee.nextbilldate, 
        users.firstname AS FirstName, 
        users.lastname AS LastName, 
        package.planname AS PackageName
    FROM
        domains
        INNER JOIN
        recurringfee
        ON 
            domains.id = recurringfee.appliestoid
        INNER JOIN
        users
        ON 
            domains.CustomerID = users.id
        INNER JOIN
        package
        ON 
            domains.Plan = package.id
    WHERE
        domains.use_custom_price = 1 AND
        domains.`status` = 1
    GROUP BY
        domains.id
    ORDER BY
        OrderID ASC";		


        $this->SetDescription($this->user->lang('Override Prices'));

		$db = $this->db->query($sql);
	


		
		while ( $row = $db->fetch() ) 
		{
			
	            $Data[]= [$row['CustomerID'] ,$row['DateActivated'],$row['Status'],$row['OrderID'],$row['PackageName'],$row['CustomPrice'],$row['nextbilldate'],$row['paymentterm'],$row['FirstName']." ".$row['LastName'] ];
        
    	}
		

        
        






    if (isset($_REQUEST['download']) && $_REQUEST['download'] == 1) {
        //csv file will exxclude any line having an empty invoice id
       
        $this->download($labels, $data, 'Transactions_By_Month.csv','');
    }



		$this->reportData[] = array (

            "group" => $Data,
            "groupname" => 'Custom Prices ( Override Prices )',
            "label" => ['CustomerID' ,'DateActivated','Status','OrderID','PackageName','CustomPrice','nextbilldate','paymentterm','ّFull Name' ]
        );
		
		
		
        
    }



}
















?>
