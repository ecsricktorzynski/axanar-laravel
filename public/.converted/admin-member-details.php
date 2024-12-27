<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wwatters
 * Date: 7/5/16
 * Time: 10:27 PM
 * To change this template use File | Settings | File Templates.
 */
require_once __DIR__ . '/bootstrap.php';
if ($_REQUEST && is_array($_REQUEST) && array_key_exists("userId",$_REQUEST))
{
    $userId = $_REQUEST['userId'];
    $u = getUserRecord($userId);
    if (is_array($u) && count($u)>0)
    {
        $u = $u[0];
        $ua = getUserAccountInfo($userId);
        if (is_array($ua) && count($ua)>0)
        {
            $ua = $ua[0];
            $name =  $u['full_name'];
            if (!empty($ua['firstName']))
            {
                $name = $ua['firstName'];
                if (!empty($ua['lastName']))
                {
                    $name .= ' ' . $ua['lastName'];
                }
            }

            if (!empty($ua['altName']))
            {
                $name .= " (listed as " .$ua['altName'] . ')';
            }
            $u['full_name']=$name;


            $u['address'] = $ua['address_1'];
            if (!is_null($ua['address_2']) && $ua['address_2']!="")
            {
                $u['address'] .= "<br>" . $ua['address_2'];
            }
            if (!is_null($ua['city']) && $ua['city']!="")
            {
                $u['address'] .= "<br>" . $ua['city'];
            }
            if (!is_null($ua['state']) && $ua['state']!="")
            {
                $u['address'] .= ", " . $ua['state'];
            }
            if (!is_null($ua['zip']) && $ua['zip']!="")
            {
                $u['address'] .= " " . $ua['zip'];
            }
            if (!is_null($ua['country']) && $ua['country']!="")
            {
                $u['address'] .= "<br>" . $ua['country'];
            }
        } else {
            $u['address'] = 'None on Record';
        }

        if (is_null($u['last_login']) || $u['last_login'] == "")
        {
            $u['last_login'] = 'Never Logged In';
        }

        $c = getCampaignsForUser($userId);
        if (is_array($c) && count($c)>0)
        {
            $u['campaigns'] = array();
            foreach ($c as $_c)
            {
                $p = getUserDonationsForCampaign((int)$_c['campaign_id'], $userId);
//                $_c['packages'] = $p;
                $_c['packages'] = array();

                if (is_array($p))
                {
                    foreach($p as $_p)
                    {
                        $package_id = $_p['package_id'];
                        $skus = getItemsForPackageByUser($package_id, $userId);
                        $_p['sku'] = $skus;
                        array_push($_c['packages'],$_p);
                    }
                }

                array_push($u['campaigns'],$_c);
            }
        } else {
            $u['campaigns'] = array();
        }
        //utf8_encode_deep($u);
        echo json_encode($u);
    }

} else {

}
