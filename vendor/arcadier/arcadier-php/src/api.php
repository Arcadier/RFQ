<?php
// namespace Arcadier;
require 'auth.php';

class ApiSdk
{
    
    private $adminToken = '';
    private $userToken = '';
    private $baseUrl    = '';
    private $marketplace = '';
    private $protocol = '';
    
    public function __construct()
    {
        // $auth = new AUTH();
        $this->adminToken = getAdminToken();
        $this->marketplace = getMarketplaceDomain();
        $this->protocol = getProtocol();
        $this->baseUrl = $this->protocol.'://'.$this->marketplace;
    }

    public function test(){
        return "Wacc";
    }

    public function callAPI($method, $access_token, $url, $data = false)
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    $jsonDataEncoded = json_encode($data);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    $jsonDataEncoded = json_encode($data);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                }
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }
        $headers = ['Content-Type: application/json'];
        if ($access_token != null && $access_token != '') {
            array_push($headers, sprintf('Authorization: Bearer %s', $access_token));
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, true);
    }

    public function getAdminId()
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        return $this->adminToken['UserId'];
    }

    ///////////////////////////////////////////////////// BEGIN USER APIs /////////////////////////////////////////////////////

    // Url in documentation doesnt have this option
    public function getUserInfo($id, $include = null)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url = $this->baseUrl . '/api/v2/users/' . $id;
        if ($include != null) {
            $url .= "?includes=" . $include;
        }
        $userInfo = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $userInfo;
    }

    //for get all users, merchants and buyers
    public function getAllUsers($keywordsParam = null)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url = $this->baseUrl . '/api/v2/admins/' .  $this->adminToken['UserId'] . '/users/';
        if ($keywordsParam != null) {
            $url .=  $keywordsParam;
        }
        $usersInfo = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $usersInfo;
    }

    public function getAllMerchants($keywordsParam = null)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url = $this->baseUrl . '/api/v2/admins/' .  $this->adminToken['UserId'] . '/users/?role=merchant';
        if ($keywordsParam != null) {
            $url .=  '&keywords'.$keywordsParam;
        }
        $usersInfo = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $usersInfo;
    }

    public function getAllBuyers($keywordsParam = null)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url = $this->baseUrl . '/api/v2/admins/' .  $this->adminToken['UserId'] . '/users/?role=buyer';
        if ($keywordsParam != null) {
            $url .=  '&keywords'.$keywordsParam;
        }
        $usersInfo = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $usersInfo;
    }

    public function registerUser($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url      = $this->baseUrl . '/api/v2/accounts/register';
        $userInfo = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $userInfo;
    }

    public function updateUserInfo($id, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url      = $this->baseUrl . '/api/v2/users/' . $id;
        $userInfo = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $userInfo;
    }

    //not working
    public function upgradeUserRole($id, $role)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }

        $url = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/users/' . $id . '/roles/' . $role;
        $userRole = $this->callAPI("PUT", $this->adminToken['access_token'], $url, null);
        return $userRole;
    }

    public function deleteUser($id)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }

        $url = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/users/' . $id;
        $deletedUser = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, null);
        return $deletedUser;
    }

    //untested
    public function getSubMerchants($merchantId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/sub-merchants';
        $submerchants = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $submerchants;
    }

    public function resetPassword($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url      = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/password';
        $response = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $response;
    }

    public function updatePassword($data, $userId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url      = $this->baseUrl . '/api/v2/users/' . $userId . '/password';
        $response = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $response;
    }

    ///////////////////////////////////////////////////// END USER APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN ADDRESS APIs /////////////////////////////////////////////////////
    public function getUserAddress($id,  $addressID)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }

        $url = $this->baseUrl . '/api/v2/users/' . $id . '/addresses/' . $addressID;
        $newAddress = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $newAddress;
    }

    public function createUserAddress($id, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }

        $url = $this->baseUrl . '/api/v2/users/' . $id . '/addresses/';
        $newAddress = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $newAddress;
    }

    public function updateUserAddress($id, $addressID, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }

        $url = $this->baseUrl . '/api/v2/users/' . $id . '/addresses/' . $addressID;
        $updatedAddress = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $updatedAddress;
    }

    public function deleteUserAddress($id, $addressID)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }

        $url = $this->baseUrl . '/api/v2/users/' . $id . '/addresses/' . $addressID;
        $deletedAddress = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, null);
        return $deletedAddress;
    }


    ///////////////////////////////////////////////////// END ADDRESS APIs /////////////////////////////////////////////////////


    ///////////////////////////////////////////////////// BEGIN ITEM APIs /////////////////////////////////////////////////////

    public function getItemInfo($id)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url      = $this->baseUrl . '/api/v2/items/' . $id;
        $itemInfo = $this->callAPI("GET", null, $url, null);
        return $itemInfo;
    }

    public function getAllItems($sortParams)
    {
        $url       = $this->baseUrl . '/api/v2/items/';
        if ($sortParams != null) {
            $url .=  $sortParams;
        }
        /* if ( isset($createdAscParam) ) {
            $url .= $createdAscParam . "&"
        };

        if ( isset($updatedAscParam) ) {
            $url .= $updatedAscParam . "&"
        };

        if ( isset($priceAscParam) ) {
            $url .= $priceAscParam . "&"
        };

        if ( isset($keywordsParam) ) {
            $url .= "keywords=" . $priceAscParam . "&"
        }; 

        if ( isset($nameParam) ) {
            $url .= $nameParam . "&"
        }; */

        $items = $this->callAPI("GET", null, $url, false);
        return $items;
    }

    public function searchItems($data)
    {
        $url       = $this->baseUrl . '/api/v2/items';
        $items = $this->callAPI("POST", null, $url, $data);
        return $items;
    }

    // For Creating an item and creating a listing
    public function createItem($data, $merchantId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/items';
        $createdItem = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $createdItem;
    }

    public function editItem($data, $merchantId, $itemId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/items/' . $itemId;
        $editedItem = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $editedItem;
    }

    public function deleteItem($merchantId, $itemId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/items/' . $itemId;
        $deletedItem = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, false);
        return $deletedItem;
    }

    public function getItemTags($pageSize = null, $pageNumber = null)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/tags/';
        if ($pageSize != null && $pageNumber != null) {
            $url .=  '?pageSize='.$pageSize.'&pageNumber='.$pageNumber;
        }
        if ($pageSize != null && $pageNumber == null) {
            $url .=  '?pageSize='.$pageSize;
        }
        if ($pageSize == null && $pageNumber != null) {
            $url .=  '?pageNumber='.$pageNumber;
        }
        $tags = $this->callAPI("GET", $this->adminToken['access_token'], $url, false);
        return $tags;
    }

    public function tagItem($data, $merchantId, $itemId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/items/' . $itemId . '/tags';
        $result = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $result;
    }

    public function deleteTags($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/tags';
        $result = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, $data);
        return $result;
    }

    ///////////////////////////////////////////////////// END ITEM APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN CART APIs /////////////////////////////////////////////////////

    public function getCart($buyerId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/users/' . $buyerId . '/carts';
        $deletedItem = $this->callAPI("GET", $this->adminToken['access_token'], $url, false);
        return $deletedItem;
    }

    public function addToCart($data, $buyerId, $username, $password)
    {
        if ($this->userToken == null) {
            $this->userToken = $this->getUserToken($username, $password);
        }
        $url       = $this->baseUrl . '/api/v2/users/' . $buyerId . '/carts';
        $cartItem = $this->callAPI("POST", $this->userToken['access_token'], $url, $data);
        return $cartItem;
    }

    public function updateCartItem($data, $buyerId, $cartItemId, $username, $password)
    {
        if ($this->userToken == null) {
            $this->userToken = $this->getUserToken($username, $password);
        }
        $url       = $this->baseUrl . '/api/v2/users/' . $buyerId . '/carts/' . $cartItemId;
        $cartItem = $this->callAPI("PUT", $this->userToken['access_token'], $url, $data);

        return $cartItem;
    }

    public function deleteCartItem($buyerId, $cartItemId, $username, $password)
    {
        if ($this->userToken == null) {
            $this->userToken = $this->getUserToken($username, $password);
        }
        $url       = $this->baseUrl . '/api/v2/users/' . $buyerId . '/carts/' . $cartItemId;
        $cartItem = $this->callAPI("DELETE", $this->userToken['access_token'], $url, null);
        return $cartItem;
    }

    ///////////////////////////////////////////////////// END CART APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN ORDER APIs /////////////////////////////////////////////////////

    public function getOrderInfoByOrderId($id, $buyerId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/users/' . $buyerId . '/orders/' . $id;
        $orderInfo = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $orderInfo;
    }

    public function updateOrders($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/orders?autoUpdatePayment=false';
        $orderInfo = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $orderInfo;
    }

    public function getOrderHistory($merchantId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/transactions';
        $orderHistory = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $orderHistory;
    }

    public function getFilteredOrderHistory($merchantId, $pageSizeParam, $pageNumberParam)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/transactions/?';
        if (isset($pageSizeParam)) {
            $url .= "pageSize=" . $pageSizeParam . "&";
        }

        if (isset($pageNumberParam)) {
            $url .= "pageNumber=" . $pageNumberParam . "&";
        }
        if (substr($url, -1) == "&") {
            $url = substr($url, 0, -1);
        }
        $orderHistory = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $orderHistory;
    }

    public function getOrderInfoByInvoiceId($merchantId, $invoiceId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/transactions/' . $invoiceId;
        $orderInfo = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $orderInfo;
    }

    public function editOrder($merchantId, $orderId, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/orders/' . $orderId;
        $updatedOrder = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $updatedOrder;
    }


    ///////////////////////////////////////////////////// END ORDER APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN TRANSACTION APIs /////////////////////////////////////////////////////


    public function getTransactionInfo($invoiceNo)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url         = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/transactions/' . $invoiceNo;
        $invoiceInfo = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $invoiceInfo;
    }

    public function updateTransactionInfo($invoiceNo, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url         = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/invoices/' . $invoiceNo;
        $invoiceInfo = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $invoiceInfo;
    }

    public function getAllTransactions()
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url         = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/transactions';
        $allTransactions = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $allTransactions;
    }

    public function getAllFilteredTransactions($pageSizeParam, $pageNumberParam, $startDateParam, $endDateParam)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }

        $url         = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/transactions/?';
        if (isset($pageSizeParam)) {
            $url .= "pageSize=" . $pageSizeParam . "&";
        }

        if (isset($pageNumberParam)) {
            $url .= "pageNumber=" . $pageNumberParam . "&";
        }

        if (isset($startDateParam)) {
            $url .= "startDate=" . $startDateParam . "&";
        }

        if (isset($endDateParam)) {
            $url .= "endDate=" . $endDateParam . "&";
        }
        if (substr($url, -1) == "&") {
            $url = substr($url, 0, -1);
        }
        $filteredTransactions = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $filteredTransactions;
    }

    public function getBuyerTransactions($buyerId, $username, $password)
    {
        if ($this->userToken == null) {
            $this->userToken = $this->getUserToken($username, $password);
        }
        $url         = $this->baseUrl . '/api/v2/users/' . $buyerId . '/transactions';
        $buyerTransactions = $this->callAPI("GET", $this->userToken['access_token'], $url, null);
        return $buyerTransactions;
    }

    ///////////////////////////////////////////////////// END TRANSACTION APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN CUSTOM TABLE APIs /////////////////////////////////////////////////////

    public function getCustomTable($packageId, $tableName)
    {
        $url         = $this->baseUrl . '/api/v2/plugins/' . $packageId . '/custom-tables/' . $tableName;
        $customTable = $this->callAPI("GET", null, $url, null);
        return $customTable;
    }

    public function createRowEntry($packageId, $tableName, $data)
    {
        $url         = $this->baseUrl . '/api/v2/plugins/' . $packageId . '/custom-tables/' . $tableName . '/rows';
        $response = $this->callAPI("POST", null, $url, $data);
        return $response;
    }

    public function editRowEntry($packageId, $tableName, $rowId, $data)
    {
        $url         = $this->baseUrl . '/api/v2/plugins/' . $packageId . '/custom-tables/' . $tableName . '/rows/' . $rowId;
        $response = $this->callAPI("PUT", null, $url, $data);
        return $response;
    }

    public function deleteRowEntry($packageId, $tableName, $rowId)
    {
        $url         = $this->baseUrl . '/api/v2/plugins/' . $packageId . '/custom-tables/' . $tableName . '/rows/' . $rowId;
        $response = $this->callAPI("DELETE", null, $url, null);
        return $response;
    }

    public function searchTable($packageId, $tableName, $data)
    {
        $url         = $this->baseUrl . '/api/v2/plugins/' . $packageId . '/custom-tables/' . $tableName;
        $rowEntries = $this->callAPI("POST", null, $url, $data);
        return $rowEntries;
    }
    ///////////////////////////////////////////////////// END CUSTOM TABLE APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN CHECKOUT APIs /////////////////////////////////////////////////////

    public function editBuyerCart($merchantId, $cartId, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/carts/' . $cartId;
        $response = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $response;
    }

    public function generateInvoice($buyerId, $data, $username, $password)
    {
        if ($this->userToken == null) {
            $this->userToken = $this->getUserToken($username, $password);
        }
        $url       = $this->baseUrl . '/api/v2/users/' . $buyerId . '/invoices/carts/';
        $response = $this->callAPI("POST", $this->userToken['access_token'], $url, $data);
        return $response;
    }

    public function updateMarketplaceTransaction($invoiceId, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/invoices/' . $invoiceId;
        $response = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $response;
    }

    ///////////////////////////////////////////////////// END CHECKOUT APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN SHIPPING APIs /////////////////////////////////////////////////////

    public function getMerchantShippingMethods($merchantId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/shipping-methods';
        $methods = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $methods;
    }

    public function getDeliveryRates()
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url = $this->baseUrl . '/api/v2/merchants/' . $this->adminToken['UserId']  . '/shipping-methods';
        $rates = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $rates;
    }

    public function createShippingMethod($merchantId, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/shipping-methods';
        $method = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $method;
    }

    //updates even though theres a code 500 exception
    public function updateShippingMethod($merchantId, $shippingMethodId, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/shipping-methods/' . $shippingMethodId;
        $method = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $method;
    }

    public function deleteShippingMethod($merchantId, $shippingMethodId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/shipping-methods/' . $shippingMethodId;
        $method = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, null);
        return $method;
    }

    ///////////////////////////////////////////////////// END SHIPPING APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN CATEGORY APIs /////////////////////////////////////////////////////

    public function getCategories()
    {
        $url       = $this->baseUrl . '/api/v2/categories';
        $categories = $this->callAPI("GET", null, $url, null);
        return $categories;
    }

    public function getCategoriesWithHierarchy()
    {
        $url       = $this->baseUrl . '/api/v2/categories/hierarchy';
        $categories = $this->callAPI("GET", null, $url, null);
        return $categories;
    }

    public function getFilteredCategories($pageSizeParam, $pageNumberParam)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/categories/?';
        if (isset($pageSizeParam)) {
            $url .= "pageSize=" . $pageSizeParam . "&";
        }

        if (isset($pageNumberParam)) {
            $url .= "pageNumber=" . $pageNumberParam . "&";
        }
        if (substr($url, -1) == "&") {
            $url = substr($url, 0, -1);
        }
        $categories = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $categories;
    }

    public function createCategory($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/categories';
        $createdCategory = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $createdCategory;
    }

    public function deleteCategory($categoryId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/categories/' . $categoryId;
        $deletedCategory = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, null);
        return $deletedCategory;
    }

    public function sortCategories($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/categories';
        $sortedCategories = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $sortedCategories;
    }

    public function updateCategory($categoryId, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url       = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/categories/' . $categoryId;
        $updatedCategory = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $updatedCategory;
    }

    ///////////////////////////////////////////////////// END CATEGORY APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN EVENT TRIGGER APIs /////////////////////////////////////////////////////


    public function getEventTriggers()
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url           = $this->baseUrl . '/api/v2/event-triggers/';
        $eventTriggers = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $eventTriggers;
    }

    public function addEventTrigger($uri, $eventIds)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/event-triggers/';
        $data = [
            'Uri'     => $uri,
            'Filters' => $eventIds,
        ];
        $eventResult = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $eventResult;
    }

    //untested
    public function updateEventTrigger($eventTriggerId, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/event-triggers/' . $eventTriggerId;
        $eventResult = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $eventResult;
    }

    public function removeEventTrigger($eventId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url         = $this->baseUrl . '/api/v2/event-triggers/' . $eventId;
        $eventResult = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, null);
        return $eventResult;
    }


    ///////////////////////////////////////////////////// END EVENT TRIGGER APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN MARKETPLACE APIs /////////////////////////////////////////////////////

    public function getMarketplaceInfo()
    {
        // $auth = new AUTH();
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url           = $this->baseUrl . '/api/v2/marketplaces/';
        $info = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $info;
    }

    public function updateMarketplaceInfo($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url           = $this->baseUrl . '/api/v2/marketplaces/';
        $info = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $info;
    }
    //untested
    public function customiseURL($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url           = $this->baseUrl . '/api/v2/rewrite-rules/';
        $response = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $response;
    }
    ///////////////////////////////////////////////////// END MARKETPLACE APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN EMAIL APIs /////////////////////////////////////////////////////

    public function sendEmail($to, $html, $subject)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/emails/';
        $data = [
            'From'    => 'admin@arcadier.com',
            'To'      => $to,
            'Body'    => $html,
            'Subject' => $subject,
        ];
        $emailResult = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $emailResult;
    }

    public function sendEmailAfterGeneratingInvoice($invoiceNo)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/emails/';
        $data = [
            'Type'    => 'invoice',
            'InvoiceNo'      => $invoiceNo
        ];
        $emailResult = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $emailResult;
    }

    ///////////////////////////////////////////////////// END EMAIL APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN CUSTOM FIELD APIs /////////////////////////////////////////////////////

    public function createCustomField($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/custom-field-definitions/';
        $createdCustomField = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $createdCustomField;
    }

    public function getCustomFields()
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/custom-field-definitions/';
        $customFields = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $customFields;
    }

    public function deleteCustomField($code)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/custom-field-definitions/' . $code;
        $deletedCustomField = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, null);
        return $deletedCustomField;
    }

    public function updateCustomField($code, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/custom-field-definitions/' . $code;
        $updatedCustomField = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $updatedCustomField;
    }

    public function getPluginCustomFields($packageId)
    {
        $url  = $this->baseUrl . '/api/v2/packages/' . $packageId . '/custom-field-definitions/';
        $customFields = $this->callAPI("GET", null, $url, null);
        return $customFields;
    }

    ///////////////////////////////////////////////////// END CUSTOM FIELD APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN PAYMENT APIs /////////////////////////////////////////////////////

    public function getPaymentGateways()
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/payment-gateways/';
        $paymentGateways = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $paymentGateways;
    }

    public function showPaymentAcceptanceMethods($merchantId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/payment-acceptance-methods/';
        $paymentAcceptanceMethods = $this->callAPI("GET", $this->adminToken['access_token'], $url, null);
        return $paymentAcceptanceMethods;
    }

    public function createPaymentGateway($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/payment-gateways/';
        $createdPaymentGateway = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $createdPaymentGateway;
    }

    public function linkPaymentGateway($merchantId, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/payment-acceptance-methods/';
        $response = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $response;
    }

    public function deletePaymentGateway($gatewayId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId'] . '/payment-gateways/' . $gatewayId;
        $deletedPaymentGateway = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, null);
        return $deletedPaymentGateway;
    }

    public function deletePaymentAcceptanceMethod($merchantId, $methodId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/merchants/' . $merchantId . '/payment-acceptance-methods/' . $methodId;
        $deletedPaymentMethod = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, null);
        return $deletedPaymentMethod;
    }

    public function updatePaymentMethod($methodCode, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/admins/' . $this->adminToken['UserId']  . '/payment-gateways/' . $methodCode;
        $updatedPaymentMethod = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $updatedPaymentMethod;
    }

    ///////////////////////////////////////////////////// END PAYMENT APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN STATIC APIs /////////////////////////////////////////////////////

    public function getFulfilmentStatuses()
    {
        $url  = $this->baseUrl . '/api/v2/static/fulfilment-statuses';
        $fulfilmentStatuses = $this->callAPI("GET", null, $url, null);
        return $fulfilmentStatuses;
    }

    public function getCurrencies()
    {
        $url  = $this->baseUrl . '/api/v2/static/currencies';
        $currencies = $this->callAPI("GET", null, $url, null);
        return $currencies;
    }

    public function getCountries()
    {
        $url  = $this->baseUrl . '/api/v2/static/countries';
        $countries = $this->callAPI("GET", null, $url, null);
        return $countries;
    }

    public function getOrderStatuses()
    {
        $url  = $this->baseUrl . '/api/v2/static/order-statuses';
        $orderStatuses = $this->callAPI("GET", null, $url, null);
        return $orderStatuses;
    }

    public function getPaymentStatuses()
    {
        $url  = $this->baseUrl . '/api/v2/static/payment-statuses';
        $paymentStatuses = $this->callAPI("GET", null, $url, null);
        return $paymentStatuses;
    }

    public function getTimezones()
    {
        $url  = $this->baseUrl . '/api/v2/static/timezones';
        $timezones = $this->callAPI("GET", null, $url, null);
        return $timezones;
    }

    ///////////////////////////////////////////////////// END STATIC APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN MEDIA APIs /////////////////////////////////////////////////////

    //dont test this yet
    public function getUserMedia($userId)
    {
        $url  = $this->baseUrl . '/api/v2/users/' . $userId . '/media';
        $userMedia = $this->callAPI("GET", null, $url, null);
        return $userMedia;
    }
    //dont test this yet
    public function updateUserMedia($userId)
    {
        $url  = $this->baseUrl . '/api/v2/users/' . $userId . '/media?purpose';
        $userMedia = $this->callAPI("POST", null, $url, null);
        return $userMedia;
    }

    ///////////////////////////////////////////////////// END MEDIA APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN PAGES APIs /////////////////////////////////////////////////////

    public function getContentPages()
    {
        $url  = $this->baseUrl . '/api/v2/content-pages';
        $pages = $this->callAPI("GET", null, $url, null);
        return $pages;
    }

    public function getPageContent($pageId)
    {
        $url  = $this->baseUrl . '/api/v2/content-pages/' . $pageId;
        $content = $this->callAPI("GET", null, $url, null);
        return $content;
    }

    public function createContentPage($data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/content-pages';
        $createdPageContent = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $createdPageContent;
    }

    public function editContentPage($pageId, $data)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/content-pages/' . $pageId;
        $editedPageContent = $this->callAPI("PUT", $this->adminToken['access_token'], $url, $data);
        return $editedPageContent;
    }

    public function deleteContentPage($pageId)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/content-pages/' . $pageId;
        $deletedPageContent = $this->callAPI("DELETE", $this->adminToken['access_token'], $url, null);
        return $deletedPageContent;
    }

    ///////////////////////////////////////////////////// END PAGES APIs /////////////////////////////////////////////////////

    ///////////////////////////////////////////////////// BEGIN PANELS APIs /////////////////////////////////////////////////////

    public function getAllPanels()
    {
        $url  = $this->baseUrl . '/api/v2/panels?type=slider';
        $panels = $this->callAPI("GET", null, $url, null);
        return $panels;
    }

    public function getPanelById($panelId)
    {
        $url  = $this->baseUrl . '/api/v2/panels/' . $panelId;
        $panel = $this->callAPI("GET", null, $url, null);
        return $panel;
    }

    ///////////////////////////////////////////////////// END PANELS APIs /////////////////////////////////////////////////////



    public function disableEdms()
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $data = [
            "Settings" => [
                "email-configuration" => [
                    "new-order"      => [
                        "enabled" => "False",
                    ],
                    "received-order" => [
                        "enabled" => "False",
                    ],
                ],
            ],
        ];
        $url           = $this->baseUrl . '/api/v2/marketplaces/';
        $eventTriggers = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $eventTriggers;
    }

    public function enabledEdms()
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $data = [
            "Settings" => [
                "email-configuration" => [
                    "new-order"      => [
                        "enabled" => "True",
                    ],
                    "received-order" => [
                        "enabled" => "True",
                    ],
                ],
            ],
        ];
        $url           = $this->baseUrl . '/api/v2/marketplaces/';
        $eventTriggers = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $eventTriggers;
    }

    public function ssoToken($exUserId, $userEmail)
    {
        if ($this->adminToken == null) {
            $this->adminToken = getAdminToken();
        }
        $url  = $this->baseUrl . '/api/v2/sso';
        $data = [
            'ExternalUserId' => $exUserId,
            'Email'          => $userEmail,
        ];
        $emailResult = $this->callAPI("POST", $this->adminToken['access_token'], $url, $data);
        return $emailResult;
    }

    public function getPackageID() {
        $requestUri = "$_SERVER[REQUEST_URI]";
        preg_match('/([a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12})/', $requestUri, $matches, 0);
        return $matches[0];
    }
}
?>