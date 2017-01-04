<?php
/** 
 * PAYONE OXID Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PAYONE OXID Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PAYONE OXID Connector.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.payone.de
 * @copyright (C) Payone GmbH
 * @version   OXID eShop CE
 */
 
class Unit_fcPayOne_Extend_Application_Controllers_fcPayOnePaymentView extends OxidTestCase {

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array()) {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Set protected/private attribute value
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $propertyName property that shall be set
     * @param array  $value value to be set
     *
     * @return mixed Method return.
     */
    public function invokeSetAttribute(&$object, $propertyName, $value) {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

    /**
     * Testing _filterDynData for having filter
     * 
     * @param void
     * @return void
     */
    public function test__filterDynData_HasFilter() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_hasFilterDynDataMethod'));
        $oTestObject->expects($this->any())->method('_hasFilterDynDataMethod')->will($this->returnValue(true));

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_filterDynData'));
    }

    /**
     * Testing _filterDynData for using method to store cc data
     * 
     * @param void
     * @return void
     */
    public function test__filterDynData_CCStored() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_hasFilterDynDataMethod'));
        $oTestObject->expects($this->any())->method('_hasFilterDynDataMethod')->will($this->returnValue(false));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_filterDynData'));
    }

    /**
     * Testing _filterDynData for case of renew cc data
     * 
     * @param void
     * @return void
     */
    public function test__filterDynData_Renew() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_hasFilterDynDataMethod'));
        $oTestObject->expects($this->any())->method('_hasFilterDynDataMethod')->will($this->returnValue(false));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));

        $aDynData = array('someValue');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($aDynData));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_filterDynData'));
    }

    /**
     * Testing init method
     * 
     * @param void
     * @return void
     */
    public function test_init_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_hasFilterDynDataMethod'));
        $oTestObject->expects($this->any())->method('_hasFilterDynDataMethod')->will($this->returnValue(false));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->onConsecutiveCalls(true, true));

        $oMockOrder = $this->getMock('oxOrder', array('load'));
        $oMockOrder->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue('cancel'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->init());
    }

    /**
     * Testing _hasFilterDynDataMethod for coverage
     * 
     * @param void
     * @return void
     */
    public function test__hasFilterDynDataMethod_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4700));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $this->invokeMethod($oTestObject, '_hasFilterDynDataMethod'));
    }

    /**
     * Testing getConfigParam for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getConfigParam_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someConfigValue'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $this->assertEquals('someConfigValue', $oTestObject->getConfigParam('someParamName'));
    }

    /**
     * Testing getMerchantId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getMerchantId_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue('someMerchantId'));

        $this->assertEquals('someMerchantId', $oTestObject->getMerchantId());
    }

    /**
     * Testing getSubAccountId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getSubAccountId_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $this->assertEquals('someValue', $oTestObject->getSubAccountId());
    }

    /**
     * Testing getPortalId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPortalId_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $this->assertEquals('someValue', $oTestObject->getPortalId());
    }

    /**
     * Testing getPortalKey for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPortalKey_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $this->assertEquals('someValue', $oTestObject->getPortalKey());
    }

    /**
     * Testing getChecktype for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getChecktype_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $this->assertEquals('someValue', $oTestObject->getChecktype());
    }

    /**
     * Testing getUserBillCountryId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getUserBillCountryId_Coverage() {
        $oMockUser = new stdClass();
        $oMockUser->oxuser__oxcountryid = new oxField('someCountryId');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $this->invokeSetAttribute($oTestObject, '_sUserBillCountryId', null);

        $this->assertEquals('someCountryId', $oTestObject->getUserBillCountryId());
    }

    /**
     * Testing getUserDelCountryId fo rcoverage
     * 
     * @param void
     * @return void
     */
    public function test_getUserDelCountryId_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockAddress = new stdClass();
        $oMockAddress->oxaddress__oxcountryid = new oxField('someCountryId');

        $oMockOrder = $this->getMock('oxOrder', array('getDelAddressInfo'));
        $oMockOrder->expects($this->any())->method('getDelAddressInfo')->will($this->returnValue($oMockAddress));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockOrder));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_sUserDelCountryId', null);

        $this->assertEquals('someCountryId', $oTestObject->getUserDelCountryId());
    }

    /**
     * Testing isPaymentMethodAvailableToUser for case delivery address
     * 
     * @param void
     * @return void
     */
    public function test_isPaymentMethodAvailableToUser_DelAddress() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUserBillCountryId', 'getUserDelCountryId'));
        $oTestObject->expects($this->any())->method('getUserBillCountryId')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getUserDelCountryId')->will($this->returnValue(true));

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals('1', $this->invokeMethod($oTestObject, 'isPaymentMethodAvailableToUser', array('paymentid', 'type')));
    }

    /**
     * Testing isPaymentMethodAvailableToUser for case bill address
     * 
     * @param void
     * @return void
     */
    public function test_isPaymentMethodAvailableToUser_BillAddress() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUserBillCountryId', 'getUserDelCountryId'));
        $oTestObject->expects($this->any())->method('getUserBillCountryId')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getUserDelCountryId')->will($this->returnValue(false));

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals('1', $this->invokeMethod($oTestObject, 'isPaymentMethodAvailableToUser', array('paymentid', 'type')));
    }

    /**
     * Testing hasPaymentMethodAvailableSubTypes for CC
     * 
     * @param void
     * @return void
     */
    public function test_hasPaymentMethodAvailableSubTypes_CC() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getVisa',
            'getMastercard',
            'getAmex',
            'getDiners',
            'getJCB',
            'getMaestroInternational',
            'getMaestroUK',
            'getDiscover',
            'getCarteBleue',
            'getSofortUeberweisung',
            'getGiropay',
            'getEPS',
            'getPostFinanceEFinance',
            'getPostFinanceCard',
            'getIdeal',
            'getP24',
                )
        );

        $oTestObject->expects($this->any())->method('getVisa')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMastercard')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getAmex')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getDiners')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getJCB')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMaestroInternational')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMaestroUK')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getDiscover')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getCarteBleue')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getSofortUeberweisung')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getGiropay')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getEPS')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getPostFinanceCard')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getIdeal')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getP24')->will($this->returnValue(false));
        $this->assertEquals(false, $oTestObject->hasPaymentMethodAvailableSubTypes('cc'));
    }

    /**
     * Testing hasPaymentMethodAvailableSubTypes for CC
     * 
     * @param void
     * @return void
     */
    public function test_hasPaymentMethodAvailableSubTypes_SB() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getVisa',
            'getMastercard',
            'getAmex',
            'getDiners',
            'getJCB',
            'getMaestroInternational',
            'getMaestroUK',
            'getDiscover',
            'getCarteBleue',
            'getSofortUeberweisung',
            'getGiropay',
            'getEPS',
            'getPostFinanceEFinance',
            'getPostFinanceCard',
            'getIdeal',
            'getP24',
                )
        );

        $oTestObject->expects($this->any())->method('getVisa')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMastercard')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getAmex')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getDiners')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getJCB')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMaestroInternational')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getMaestroUK')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getDiscover')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getCarteBleue')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getSofortUeberweisung')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getGiropay')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getEPS')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getPostFinanceCard')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getIdeal')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getP24')->will($this->returnValue(false));
        $this->assertEquals(false, $oTestObject->hasPaymentMethodAvailableSubTypes('sb'));
    }

    /**
     * Testing getDefaultOnlineUeberweisung for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getDefaultOnlineUeberweisung_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getSofortUeberweisung',
            'getGiropay',
            'getEPS',
            'getPostFinanceEFinance',
            'getPostFinanceCard',
            'getIdeal',
            'getP24',
                )
        );
        $oTestObject->expects($this->any())->method('getSofortUeberweisung')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getGiropay')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getEPS')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getPostFinanceEFinance')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getPostFinanceCard')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getIdeal')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('getP24')->will($this->returnValue(false));
        $this->assertEquals('', $oTestObject->getDefaultOnlineUeberweisung());
    }

    /**
     * Testing getVisa vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getVisa_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getVisa());
    }

    /**
     * Testing getMastercard vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getMastercard_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getMastercard());
    }

    /**
     * Testing getAmex vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getAmex_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getAmex());
    }

    /**
     * Testing getDiners vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getDiners_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getDiners());
    }

    /**
     * Testing getJCB vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getJCB_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getJCB());
    }

    /**
     * Testing getMaestroInternational vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getMaestroInternational_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getMaestroInternational());
    }

    /**
     * Testing getMaestroUK vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getMaestroUK_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getMaestroUK());
    }

    /**
     * Testing getDiscover vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getDiscover_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getDiscover());
    }

    /**
     * Testing getCarteBleue vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getCarteBleue_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getCarteBleue());
    }

    /**
     * Testing getSofortUeberweisung vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getSofortUeberweisung_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getSofortUeberweisung());
    }

    /**
     * Testing getGiropay vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getGiropay_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getGiropay());
    }

    /**
     * Testing getEPS vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getEPS_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getEPS());
    }

    /**
     * Testing getPostFinanceEFinance vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPostFinanceEFinance_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getPostFinanceEFinance());
    }

    /**
     * Testing getPostFinanceCard vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPostFinanceCard_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getPostFinanceCard());
    }

    /**
     * Testing getIdeal vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIdeal_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getIdeal());
    }

    /**
     * Testing getP24 vor Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getP24_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'isPaymentMethodAvailableToUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('isPaymentMethodAvailableToUser')->will($this->returnValue(true));
        $this->assertEquals(true, $oTestObject->getP24());
    }

    /**
     * Testing get encoding for utf8
     * 
     * @param void
     * @return void
     */
    public function test_getEncoding_Utf8() {
        $oMockConfig = $this->getMock('oxConfig', array('isUtf'));
        $oMockConfig->expects($this->any())->method('isUtf')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $this->assertEquals('UTF-8', $oTestObject->getEncoding());
    }

    /**
     * Testing get encoding for ascii
     * 
     * @param void
     * @return void
     */
    public function test_getEncoding_NoUtf8() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('isUtf')->will($this->returnValue(false));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $this->assertEquals('ISO-8859-1', $oTestObject->getEncoding());
    }

    /**
     * Testing getAmount for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getAmount_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockPrice = $this->getMock('oxPrice', array('getBruttoPrice'));
        $oMockPrice->expects($this->any())->method('getBruttoPrice')->will($this->returnValue(1.99));

        $oMockBasket = $this->getMock('oxBasket', array('getPrice'));
        $oMockBasket->expects($this->any())->method('getPrice')->will($this->returnValue($oMockPrice));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(199, $oTestObject->getAmount());
    }

    /**
     * Testing getTplLang for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getTplLang_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockLang = $this->getMock('oxLang', array('getLanguageAbbr'));
        $oMockLang->expects($this->any())->method('getLanguageAbbr')->will($this->returnValue('DE'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('DE', $oTestObject->getTplLang());
    }

    /**
     * Testing fcGetLangId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcGetLangId_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockLang = $this->getMock('oxLang', array('getBaseLanguage'));
        $oMockLang->expects($this->any())->method('getBaseLanguage')->will($this->returnValue(0));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(0, $oTestObject->fcGetLangId());
    }

    /**
     * Testing getHashCC for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getHashCC_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $sResponse = $sExpect = $oTestObject->getHashCC('test');

        $this->assertEquals($sExpect, $sResponse);
    }

    /**
     * Testing fcpoGetCCPaymentMetaData for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetCCPaymentMetaData_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getVisa',
            'getMastercard',
            'getAmex',
            'getDiners',
            'getJCB',
            'getMaestroInternational',
            'getMaestroUK',
            'getDiscover',
            'getCarteBleue',
            '_fcpoGetCCPaymentMetaData',
                )
        );

        $oTestObject->expects($this->any())->method('getVisa')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getMastercard')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getAmex')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getDiners')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getJCB')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getMaestroInternational')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getMaestroUK')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getDiscover')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getCarteBleue')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetCCPaymentMetaData')->will($this->returnValue('someValue'));

        $aExpect = array('someValue', 'someValue', 'someValue', 'someValue', 'someValue', 'someValue', 'someValue', 'someValue', 'someValue');
        $aResponse = $oTestObject->fcpoGetCCPaymentMetaData();

        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing fcpoGetOnlinePaymentMetaData for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetOnlinePaymentMetaData_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getSofortUeberweisung',
            'getGiropay',
            'getEPS',
            'getPostFinanceEFinance',
            'getPostFinanceCard',
            'getIdeal',
            'getP24',
            '_fcpoGetOnlinePaymentData',
                )
        );

        $oTestObject->expects($this->any())->method('getSofortUeberweisung')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getGiropay')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getEPS')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getPostFinanceEFinance')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getPostFinanceCard')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getIdeal')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getP24')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoGetOnlinePaymentData')->will($this->returnValue('someValue'));

        $aExpect = array('someValue', 'someValue', 'someValue', 'someValue', 'someValue', 'someValue', 'someValue');
        $aResponse = $oTestObject->fcpoGetOnlinePaymentMetaData();

        $this->assertEquals($aExpect, $aResponse);
    }

    /**
     * Testing _fcpoGetOnlinePaymentData for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoGetOnlinePaymentData_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getDynValue'));

        $sIdent = 'P24';
        $aDynValue['fcpo_sotype'] = $sIdent;
        $oTestObject->expects($this->any())->method('getDynValue')->will($this->returnValue($aDynValue));

        $oExpectPaymentMetaData = new stdClass();
        $oExpectPaymentMetaData->sShortcut = $sIdent;
        $oExpectPaymentMetaData->sCaption = 'P24';
        $oExpectPaymentMetaData->blSelected = true;

        $oResponse = $this->invokeMethod($oTestObject, '_fcpoGetOnlinePaymentData', array($sIdent));
        $this->assertEquals($oExpectPaymentMetaData, $oResponse);
    }

    /**
     * Testing _fcpoGetCCPaymentMetaData for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoGetCCPaymentMetaData_Coverage() {
        $sPaymentTag = 'someTag';
        $sPaymentName = 'someName';
        $aDynValue['fcpo_kktype'] = $sPaymentTag;
        $sHashCC = md5('12345');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getDynValue', 'getHashCC'));
        $oTestObject->expects($this->any())->method('getDynValue')->will($this->returnValue($aDynValue));
        $oTestObject->expects($this->any())->method('getHashCC')->will($this->returnValue($sHashCC));

        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcpoGetOperationMode'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockPayment->expects($this->any())->method('fcpoGetOperationMode')->will($this->returnValue('test'));

        $oExpectPaymentMetaData = new stdClass();
        $oExpectPaymentMetaData->sHashName = 'fcpo_hashcc_' . $sPaymentTag;
        $oExpectPaymentMetaData->sHashValue = $sHashCC;
        $oExpectPaymentMetaData->sOperationModeName = "fcpo_mode_someId_" . $sPaymentTag;
        $oExpectPaymentMetaData->sOperationModeValue = 'test';
        $oExpectPaymentMetaData->sPaymentTag = $sPaymentTag;
        $oExpectPaymentMetaData->sPaymentName = $sPaymentName;
        $oExpectPaymentMetaData->blSelected = true;

        $oResponse = $this->invokeMethod($oTestObject, '_fcpoGetCCPaymentMetaData', array($oMockPayment, $sPaymentTag, $sPaymentName));
        $this->assertEquals($oExpectPaymentMetaData, $oResponse);
    }

    /**
     * Testing _getOperationModeELV for coverage
     * 
     * @param void
     * @eturn void
     */
    public function test__getOperationModeELV_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcpoGetOperationMode'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcpoGetOperationMode')->will($this->returnValue('test'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('test', $this->invokeMethod($oTestObject, '_getOperationModeELV'));
    }

    /**
     * Testing getHashELVWithChecktype for Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getHashELVWithChecktype_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getSubAccountId',
            'getChecktype',
            'getEncoding',
            'getMerchantId',
            '_getOperationModeELV',
            'getPortalId',
            'getPortalKey',
                )
        );
        $oTestObject->expects($this->any())->method('getSubAccountId')->will($this->returnValue('someSubaccountId'));
        $oTestObject->expects($this->any())->method('getChecktype')->will($this->returnValue('someChecktype'));
        $oTestObject->expects($this->any())->method('getEncoding')->will($this->returnValue('someEncoding'));
        $oTestObject->expects($this->any())->method('getMerchantId')->will($this->returnValue('someMerchantId'));
        $oTestObject->expects($this->any())->method('_getOperationModeELV')->will($this->returnValue('test'));
        $oTestObject->expects($this->any())->method('getPortalId')->will($this->returnValue('somePortalId'));
        $oTestObject->expects($this->any())->method('getPortalKey')->will($this->returnValue('somePortalKey'));

        $sExpectHash = md5('someSubaccountIdsomeChecktypesomeEncodingsomeMerchantIdtestsomePortalIdbankaccountcheckJSONsomePortalKey');

        $this->assertEquals($sExpectHash, $this->invokeMethod($oTestObject, 'getHashELVWithChecktype'));
    }

    /**
     * Testing getHashELVWithoutChecktype for Coverage
     * 
     * @param void
     * @return void
     */
    public function test_getHashELVWithoutChecktype_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'getSubAccountId',
            'getEncoding',
            'getMerchantId',
            '_getOperationModeELV',
            'getPortalId',
            'getPortalKey',
                )
        );
        $oTestObject->expects($this->any())->method('getSubAccountId')->will($this->returnValue('someSubaccountId'));
        $oTestObject->expects($this->any())->method('getEncoding')->will($this->returnValue('someEncoding'));
        $oTestObject->expects($this->any())->method('getMerchantId')->will($this->returnValue('someMerchantId'));
        $oTestObject->expects($this->any())->method('_getOperationModeELV')->will($this->returnValue('test'));
        $oTestObject->expects($this->any())->method('getPortalId')->will($this->returnValue('somePortalId'));
        $oTestObject->expects($this->any())->method('getPortalKey')->will($this->returnValue('somePortalKey'));

        $sExpectHash = md5('someSubaccountIdsomeEncodingsomeMerchantIdtestsomePortalIdbankaccountcheckJSONsomePortalKey');

        $this->assertEquals($sExpectHash, $this->invokeMethod($oTestObject, 'getHashELVWithoutChecktype'));
    }

    /**
     * Testing getPaymentList for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPaymentList_Coverage_1() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oMockUser = $this->getMock('oxUser', array('checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));

        $this->invokeSetAttribute($oTestObject, '_oPaymentList', null);

        $mResponse = $mExpect = $this->invokeMethod($oTestObject, 'getPaymentList');

        $this->assertEquals($mExpect, $mResponse);
    }

    /**
     * Testing getPaymentList for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getPaymentList_Coverage_2() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oMockUser = $this->getMock('oxUser', array('checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(false));

        $oMockUtils = $this->getMock('oxUtils', array('redirect'));
        $oMockUtils->expects($this->any())->method('redirect')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));

        $this->invokeSetAttribute($oTestObject, '_oPaymentList', null);
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $mResponse = $mExpect = $this->invokeMethod($oTestObject, 'getPaymentList');

        $this->assertEquals($mExpect, $mResponse);
    }

    /**
     * Testing fcpoGetCreditcardType for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetCreditcardType_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $this->assertEquals('someValue', $this->invokeMethod($oTestObject, 'fcpoGetCreditcardType'));
    }

    /**
     * Testing _fcpoCheckPaypalExpressRemoval for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoCheckPaypalExpressRemoval_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $this->invokeSetAttribute($oTestObject, '_oPaymentList', array('fcpopaypal_express' => 'someValue'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(false));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoCheckPaypalExpressRemoval'));
    }

    /**
     * Testing _fcpoKlarnaUpdateUser for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoKlarnaUpdateUser_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));

        $oMockAddress = $this->getMock('oxAddress', array('load', 'save'));
        $oMockAddress->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockAddress->expects($this->any())->method('save')->will($this->returnValue(true));

        $sType = 'kls';
        $aDynValue = array(
            'fcpo_' . $sType . '_fon' => '123456',
            'fcpo_' . $sType . '_birthday' => 'someBirthday',
            'fcpo_' . $sType . '_personalid' => 'someId',
            'fcpo_' . $sType . '_sal' => 'someSal',
            'fcpo_' . $sType . '_addinfo' => 'someAddinfo',
            'fcpo_' . $sType . '_del_addinfo' => 'someDelAddinfo',
        );

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getDynValue', 'getUser'));
        $oTestObject->expects($this->any())->method('getDynValue')->will($this->returnValue($aDynValue));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcpoKlarnaUpdateUser'));
    }

    /**
     * Testing validatePayment coverage
     * 
     * @param void
     * @return void
     */
    public function test_validatePayment_Coverage_1() {
        $aRequestResponse = array(
            'status' => 'BLUBB',
        );

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestManagemandate'));
        $oMockRequest->expects($this->any())->method('sendRequestManagemandate')->will($this->returnValue($aRequestResponse));

        $oMockBasket = $this->getMock('oxBasket', array('setTsProductId', 'getPriceForPayment'));
        $oMockBasket->expects($this->any())->method('setTsProductId')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getPriceForPayment')->will($this->returnValue(true));

        $oMockSession = $this->getMock('oxSession', array('getBasket', 'getVariable', 'setVariable'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));
        $oMockSession->expects($this->any())->method('getVariable')->will($this->returnValue('someDamnId'));
        $oMockSession->expects($this->any())->method('setVariable')->will($this->returnValue(true));

        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcBoniCheckNeeded', 'isValidPayment'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('isValidPayment')->will($this->returnValue(true));
        $oMockPayment->oxpayments__oxfromboni = new oxField(5000);

        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId', 'checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->oxuser__oxboni = new oxField(5000);

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->onConsecutiveCalls('after', 'someValue'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoKlarnaUpdateUser', 'getUser', '_processParentReturnValue', '_fcGetCurrentVersion'));
        $oTestObject->expects($this->any())->method('_fcpoKlarnaUpdateUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_processParentReturnValue')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4700));

        $sRequestPaymentId = 'fcpoklarna';
        $sKlarnaRequestCampaign = 'someCampaign';

        $aApproval[$sRequestPaymentId] = 'false';

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls($sRequestPaymentId, true, true, $aApproval, $sRequestPaymentId, false));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($sRequestPaymentId));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockPayment, $oMockRequest));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));


        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $this->invokeMethod($oTestObject, 'validatePayment'));
    }

    /**
     * Testing validatePayment coverage
     * 
     * @param void
     * @return void
     */
    public function test_validatePayment_Coverage_2() {
        $aRequestResponse = array(
            'status' => 'ERROR',
        );

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestManagemandate'));
        $oMockRequest->expects($this->any())->method('sendRequestManagemandate')->will($this->returnValue($aRequestResponse));

        $oMockBasket = $this->getMock('oxBasket', array('setTsProductId', 'getPriceForPayment'));
        $oMockBasket->expects($this->any())->method('setTsProductId')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getPriceForPayment')->will($this->returnValue(true));

        $oMockSession = $this->getMock('oxSession', array('getBasket', 'getVariable', 'setVariable'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));
        $oMockSession->expects($this->any())->method('getVariable')->will($this->returnValue('someDamnId'));
        $oMockSession->expects($this->any())->method('setVariable')->will($this->returnValue(true));

        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcBoniCheckNeeded', 'isValidPayment'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('isValidPayment')->will($this->returnValue(true));
        $oMockPayment->oxpayments__oxfromboni = new oxField(5000);

        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId', 'checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->oxuser__oxboni = new oxField(5000);

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoKlarnaUpdateUser', 'getUser', '_processParentReturnValue', '_fcGetCurrentVersion'));
        $oTestObject->expects($this->any())->method('_fcpoKlarnaUpdateUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_processParentReturnValue')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4700));

        $sRequestPaymentId = 'fcpoklarna';
        $sKlarnaRequestCampaign = 'someCampaign';

        $aApproval[$sRequestPaymentId] = 'false';

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls($sRequestPaymentId, false, $aApproval, $sRequestPaymentId, false));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($sRequestPaymentId));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockPayment, $oMockRequest));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));


        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $this->invokeMethod($oTestObject, 'validatePayment'));
    }

    /**
     * Testing validatePayment coverage
     * 
     * @param void
     * @return void
     */
    public function test_validatePayment_Coverage_3() {
        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $aRequestResponse = array(
            'status' => 'ERROR',
        );

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestManagemandate'));
        $oMockRequest->expects($this->any())->method('sendRequestManagemandate')->will($this->returnValue($aRequestResponse));

        $oMockBasket = $this->getMock('oxBasket', array('setTsProductId', 'getPriceForPayment'));
        $oMockBasket->expects($this->any())->method('setTsProductId')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getPriceForPayment')->will($this->returnValue(true));

        $oMockSession = $this->getMock('oxSession', array('getBasket', 'getVariable', 'setVariable'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));
        $oMockSession->expects($this->any())->method('getVariable')->will($this->returnValue('someDamnId'));
        $oMockSession->expects($this->any())->method('setVariable')->will($this->returnValue(true));

        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcBoniCheckNeeded', 'isValidPayment', 'getId'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('isValidPayment')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('fcpodebitnote'));
        $oMockPayment->oxpayments__oxfromboni = new oxField(5000);

        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId', 'checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->oxuser__oxboni = new oxField(4000);

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoKlarnaUpdateUser', 'getUser', '_processParentReturnValue', '_fcGetCurrentVersion'));
        $oTestObject->expects($this->any())->method('_fcpoKlarnaUpdateUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_processParentReturnValue')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4700));

        $sRequestPaymentId = 'fcpoklarna';
        $sKlarnaRequestCampaign = 'someCampaign';

        $aApproval[$sRequestPaymentId] = 'somethingDifferent';

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls($sRequestPaymentId, false, $aApproval, $sRequestPaymentId, false));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($sRequestPaymentId));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockPayment, $oMockRequest));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));


        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $this->invokeMethod($oTestObject, 'validatePayment'));
    }

    /**
     * Testing validatePayment coverage
     * 
     * @param void
     * @return void
     */
    public function test_validatePayment_Coverage_4() {
        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $aRequestResponse = array(
            'status' => 'ERROR',
        );

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestManagemandate'));
        $oMockRequest->expects($this->any())->method('sendRequestManagemandate')->will($this->returnValue($aRequestResponse));

        $oMockBasket = $this->getMock('oxBasket', array('setTsProductId', 'getPriceForPayment'));
        $oMockBasket->expects($this->any())->method('setTsProductId')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getPriceForPayment')->will($this->returnValue(true));

        $oMockSession = $this->getMock('oxSession', array('getBasket', 'getVariable', 'setVariable'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));
        $oMockSession->expects($this->any())->method('getVariable')->will($this->returnValue('someDamnId'));
        $oMockSession->expects($this->any())->method('setVariable')->will($this->returnValue(true));

        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcBoniCheckNeeded', 'isValidPayment', 'getId'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(false));
        $oMockPayment->expects($this->any())->method('isValidPayment')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('fcpodebitnote'));
        $oMockPayment->oxpayments__oxfromboni = new oxField(5000);

        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId', 'checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->oxuser__oxboni = new oxField(4000);

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoKlarnaUpdateUser', 'getUser', '_processParentReturnValue', '_fcGetCurrentVersion'));
        $oTestObject->expects($this->any())->method('_fcpoKlarnaUpdateUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_processParentReturnValue')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4700));

        $sRequestPaymentId = 'fcpoklarna';
        $sKlarnaRequestCampaign = 'someCampaign';

        $aApproval[$sRequestPaymentId] = 'somethingDifferent';

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls($sRequestPaymentId, false, $aApproval, $sRequestPaymentId, false));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($sRequestPaymentId));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockPayment, $oMockRequest));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));


        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $this->invokeMethod($oTestObject, 'validatePayment'));
    }

    /**
     * Testing validatePayment coverage
     * 
     * @param void
     * @return void
     */
    public function test_validatePayment_Coverage_5() {
        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $aRequestResponse = array(
            'mandate_status' => 'BLABLA',
        );

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestManagemandate'));
        $oMockRequest->expects($this->any())->method('sendRequestManagemandate')->will($this->returnValue($aRequestResponse));

        $oMockBasket = $this->getMock('oxBasket', array('setTsProductId', 'getPriceForPayment'));
        $oMockBasket->expects($this->any())->method('setTsProductId')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getPriceForPayment')->will($this->returnValue(true));

        $oMockSession = $this->getMock('oxSession', array('getBasket', 'getVariable', 'setVariable'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));
        $oMockSession->expects($this->any())->method('getVariable')->will($this->returnValue('someDamnId'));
        $oMockSession->expects($this->any())->method('setVariable')->will($this->returnValue(true));

        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcBoniCheckNeeded', 'isValidPayment', 'getId'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(false));
        $oMockPayment->expects($this->any())->method('isValidPayment')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('fcpodebitnote'));
        $oMockPayment->oxpayments__oxfromboni = new oxField(5000);

        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId', 'checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->oxuser__oxboni = new oxField(4000);

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoKlarnaUpdateUser', 'getUser', '_processParentReturnValue', '_fcGetCurrentVersion'));
        $oTestObject->expects($this->any())->method('_fcpoKlarnaUpdateUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_processParentReturnValue')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4700));

        $sRequestPaymentId = 'fcpoklarna';
        $sKlarnaRequestCampaign = 'someCampaign';

        $aApproval[$sRequestPaymentId] = 'somethingDifferent';

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls($sRequestPaymentId, false, $aApproval, $sRequestPaymentId, true, false));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($sRequestPaymentId));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockPayment, $oMockRequest));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));


        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $this->invokeMethod($oTestObject, 'validatePayment'));
    }

    /**
     * Testing validatePayment coverage
     * 
     * @param void
     * @return void
     */
    public function test_validatePayment_Coverage_6() {
        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $aRequestResponse = array(
            'status' => 'ERROR',
        );

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestManagemandate'));
        $oMockRequest->expects($this->any())->method('sendRequestManagemandate')->will($this->returnValue($aRequestResponse));

        $oMockBasket = $this->getMock('oxBasket', array('setTsProductId', 'getPriceForPayment'));
        $oMockBasket->expects($this->any())->method('setTsProductId')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getPriceForPayment')->will($this->returnValue(true));

        $oMockSession = $this->getMock('oxSession', array('getBasket', 'getVariable', 'setVariable'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));
        $oMockSession->expects($this->any())->method('getVariable')->will($this->returnValue('someDamnId'));
        $oMockSession->expects($this->any())->method('setVariable')->will($this->returnValue(true));

        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcBoniCheckNeeded', 'isValidPayment', 'getId'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('isValidPayment')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('fcpodebitnote'));
        $oMockPayment->oxpayments__oxfromboni = new oxField(5000);

        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId', 'checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->oxuser__oxboni = new oxField(4000);

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoKlarnaUpdateUser', 'getUser', '_processParentReturnValue', '_fcGetCurrentVersion'));
        $oTestObject->expects($this->any())->method('_fcpoKlarnaUpdateUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_processParentReturnValue')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4700));

        $sRequestPaymentId = 'fcpoklarna';
        $sKlarnaRequestCampaign = 'someCampaign';

        $aApproval[$sRequestPaymentId] = 'somethingDifferent';

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls(false, false, $aApproval, false, false));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($sRequestPaymentId));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockPayment, $oMockRequest));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));


        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $this->invokeMethod($oTestObject, 'validatePayment'));
    }

    /**
     * Testing validatePayment coverage
     * 
     * @param void
     * @return void
     */
    public function test_validatePayment_Coverage_7() {
        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $aRequestResponse = array(
            'status' => 'ERROR',
        );

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestManagemandate'));
        $oMockRequest->expects($this->any())->method('sendRequestManagemandate')->will($this->returnValue($aRequestResponse));

        $oMockBasket = $this->getMock('oxBasket', array('setTsProductId', 'getPriceForPayment'));
        $oMockBasket->expects($this->any())->method('setTsProductId')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getPriceForPayment')->will($this->returnValue(true));

        $oMockSession = $this->getMock('oxSession', array('getBasket', 'getVariable', 'setVariable'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));
        $oMockSession->expects($this->any())->method('getVariable')->will($this->returnValue('someDamnId'));
        $oMockSession->expects($this->any())->method('setVariable')->will($this->returnValue(true));

        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcBoniCheckNeeded', 'isValidPayment', 'getId'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('isValidPayment')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('fcpodebitnote'));
        $oMockPayment->oxpayments__oxfromboni = new oxField(5000);

        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId', 'checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->oxuser__oxboni = new oxField(4000);

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoKlarnaUpdateUser', 'getUser', '_processParentReturnValue', '_fcGetCurrentVersion'));
        $oTestObject->expects($this->any())->method('_fcpoKlarnaUpdateUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_processParentReturnValue')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4700));

        $sRequestPaymentId = 'fcpoklarna';
        $sKlarnaRequestCampaign = 'someCampaign';

        $aApproval[$sRequestPaymentId] = 'somethingDifferent';

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls(false, false, $aApproval, false, false, false));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($sRequestPaymentId));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockPayment, $oMockRequest));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));


        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $this->invokeMethod($oTestObject, 'validatePayment'));
    }

    /**
     * Testing validatePayment coverage
     * 
     * @param void
     * @return void
     */
    public function test_validatePayment_Coverage_8() {
        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue(true));

        $aRequestResponse = array(
            'status' => 'ERROR',
        );

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestManagemandate'));
        $oMockRequest->expects($this->any())->method('sendRequestManagemandate')->will($this->returnValue($aRequestResponse));

        $oMockBasket = $this->getMock('oxBasket', array('setTsProductId', 'getPriceForPayment'));
        $oMockBasket->expects($this->any())->method('setTsProductId')->will($this->returnValue(true));
        $oMockBasket->expects($this->any())->method('getPriceForPayment')->will($this->returnValue(true));

        $oMockSession = $this->getMock('oxSession', array('getBasket', 'getVariable', 'setVariable'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));
        $oMockSession->expects($this->any())->method('getVariable')->will($this->returnValue('someDamnId'));
        $oMockSession->expects($this->any())->method('setVariable')->will($this->returnValue(true));

        $oMockPayment = $this->getMock('oxPayment', array('load', 'fcBoniCheckNeeded', 'isValidPayment', 'getId'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(false));
        $oMockPayment->expects($this->any())->method('isValidPayment')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('fcpodebitnote'));
        $oMockPayment->oxpayments__oxfromboni = new oxField(5000);

        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId', 'checkAddressAndScore'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->oxuser__oxboni = new oxField(4000);

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoKlarnaUpdateUser', 'getUser', '_processParentReturnValue', '_fcGetCurrentVersion'));
        $oTestObject->expects($this->any())->method('_fcpoKlarnaUpdateUser')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_processParentReturnValue')->will($this->returnValue('order'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4700));

        $sRequestPaymentId = 'fcpoklarna';
        $sKlarnaRequestCampaign = 'someCampaign';

        $aApproval[$sRequestPaymentId] = 'somethingDifferent';

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->onConsecutiveCalls($sRequestPaymentId, false, $aApproval, false, false));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue($sRequestPaymentId));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockPayment, $oMockRequest));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));


        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $this->invokeMethod($oTestObject, 'validatePayment'));
    }

    /**
     * Testing _processParentReturnValue for coverage
     * 
     * @param void
     * @return void
     */
    public function test__processParentReturnValue_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $this->assertEquals('someValue', $this->invokeMethod($oTestObject, '_processParentReturnValue', array('someValue')));
    }

    /**
     * Testing fcGetApprovalText for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcGetApprovalText_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someValue'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $this->assertEquals('someValue', $oTestObject->fcGetApprovalText());
    }

    /**
     * Testing fcShowApprovalMessage for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcShowApprovalMessage_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $this->assertEquals(true, $oTestObject->fcShowApprovalMessage());
    }

    /**
     * Testing getIntegratorid for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIntegratorid_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntegratorId')->will($this->returnValue('someIntegratorId'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someIntegratorId', $oTestObject->getIntegratorid());
    }

    /**
     * Testing getIntegratorver for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIntegratorver_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntegratorVersion')->will($this->returnValue('someIntegratorVersion'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someIntegratorVersion', $oTestObject->getIntegratorver());
    }

    /**
     * Testing getIntegratorextver for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIntegratorextver_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetModuleVersion')->will($this->returnValue('someModuleVersion'));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('someModuleVersion', $oTestObject->getIntegratorextver());
    }

    /**
     * Testing fcpoGetConfirmationText for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetConfirmationText_Coverage() {
        $sId = 'someKlarnaStoreId';
        $sKlarnaLang = '';
        $sConfirmText = 'someConfirmText';

        $oMockPayment = $this->getMock('oxpayment', array('fcpoGetKlarnaStoreId'));
        $oMockPayment->expects($this->any())->method('fcpoGetKlarnaStoreId')->will($this->returnValue(''));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoGetKlarnaLang'));
        $oTestObject->expects($this->any())->method('_fcpoGetKlarnaLang')->will($this->returnValue($sKlarnaLang));

        $oMockLang = $this->getMock('oxLang', array('translateString'));
        $oMockLang->expects($this->any())->method('translateString')->will($this->returnValue($sConfirmText));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $sExpect = $sConfirmText;
        $this->assertEquals($sExpect, $oTestObject->fcpoGetConfirmationText());
    }

    /**
     * Testing fcpoKlarnaIsTelephoneNumberNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsTelephoneNumberNeeded_Coverage() {
        $oMockUser = new stdClass();
        $oMockUser->oxuser__oxfon = new oxField('123456789');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $this->assertEquals(false, $oTestObject->fcpoKlarnaIsTelephoneNumberNeeded());
    }

    /**
     * Testing fcpoKlarnaIsBirthdayNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsBirthdayNeeded_Coverage() {
        $oMockCountry = new stdClass();
        $oMockCountry->oxcountry__oxisoalpha2 = new oxField('DE');

        $oMockUser = $this->getMock('oxUser', array('getUserCountry'));
        $oMockUser->expects($this->any())->method('getUserCountry')->will($this->returnValue($oMockCountry));
        $oMockUser->oxuser__oxbirthdate = new oxField('0000-00-00');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $this->assertEquals(true, $oTestObject->fcpoKlarnaIsBirthdayNeeded());
    }

    /**
     * Testing fcpoKlarnaIsAddressAdditionNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsAddressAdditionNeeded_Coverage() {
        $oMockUser = new stdClass();
        $oMockUser->oxuser__oxaddinfo = new oxField('');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('fcGetBillCountry')->will($this->returnValue('nl'));

        $this->assertEquals(true, $oTestObject->fcpoKlarnaIsAddressAdditionNeeded());
    }

    /**
     * Testing fcpoKlarnaIsDelAddressAdditionNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsDelAddressAdditionNeeded_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('getSelectedAddressId'));
        $oMockUser->expects($this->any())->method('getSelectedAddressId')->will($this->returnValue('someAddressId'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('fcGetBillCountry')->will($this->returnValue('nl'));

        $oMockAddress = $this->getMock('oxAddress', array('load'));
        $oMockAddress->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockAddress->oxaddress__oxaddinfo = new oxField(false);

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockAddress));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->fcpoKlarnaIsDelAddressAdditionNeeded());
    }

    /**
     * Testing fcpoKlarnaIsGenderNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsGenderNeeded_Coverage() {
        $oMockUser = new stdClass();
        $oMockUser->oxuser__oxsal = new oxField(false);

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('fcGetBillCountry')->will($this->returnValue('nl'));

        $this->assertEquals(true, $oTestObject->fcpoKlarnaIsGenderNeeded());
    }

    /**
     * Testing fcpoKlarnaIsPersonalIdNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaIsPersonalIdNeeded_Coverage() {
        $oMockUser = new stdClass();
        $oMockUser->oxuser__fcpopersonalid = new oxField(false);

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('fcGetBillCountry')->will($this->returnValue('dk'));

        $this->assertEquals(true, $oTestObject->fcpoKlarnaIsPersonalIdNeeded());
    }

    /**
     * Testing fcpoKlarnaInfoNeeded for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoKlarnaInfoNeeded_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
            'fcpoKlarnaIsTelephoneNumberNeeded',
            'fcpoKlarnaIsBirthdayNeeded',
            'fcpoKlarnaIsAddressAdditionNeeded',
            'fcpoKlarnaIsDelAddressAdditionNeeded',
            'fcpoKlarnaIsGenderNeeded',
            'fcpoKlarnaIsPersonalIdNeeded',
                )
        );
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsTelephoneNumberNeeded')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsBirthdayNeeded')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsAddressAdditionNeeded')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsDelAddressAdditionNeeded')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsGenderNeeded')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('fcpoKlarnaIsPersonalIdNeeded')->will($this->returnValue(false));

        $this->assertEquals(false, $oTestObject->fcpoKlarnaInfoNeeded());
    }

    /**
     * Testing fcpoGetDebitCountries for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetDebitCountries_Coverage() {
        $aCountries = array('a7c40f631fc920687.20179984');

        $oMockPayment = $this->getMock('oxPayment', array('fcpoGetCountryIsoAlphaById', 'fcpoGetCountryNameById'));
        $oMockPayment->expects($this->any())->method('fcpoGetCountryIsoAlphaById')->will($this->returnValue('DE'));
        $oMockPayment->expects($this->any())->method('fcpoGetCountryNameById')->will($this->returnValue('Deutschland'));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue($aCountries));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aExpect = array();
        $aExpect['DE'] = 'Deutschland';

        $this->assertEquals($aExpect, $oTestObject->fcpoGetDebitCountries());
    }

    /**
     * Testing fcpoShowOldDebitFields for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoShowOldDebitFields_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', 'fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->fcpoShowOldDebitFields());
    }

    /**
     * Testing _fcCleanupSessionFragments for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcCleanupSessionFragments_Coverage() {
        $oMockPayment = $this->getMock('oxPayment', array('getId'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $oTestObject = oxNew('fcPayOnePaymentView');
        $this->assertEquals(null, $this->invokeMethod($oTestObject, '_fcCleanupSessionFragments', array($oMockPayment)));
    }

    /**
     * Testing _fcGetPaymentByPaymentType for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcGetPaymentByPaymentType_Positive() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockUser = $this->getMock('oxUser', array('getId'));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $sMockPaymentType = 'fcpopayadvance';

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));

        $oMockUserPayment = $this->getMock('oxuserpayment', array('load'));
        $oMockUserPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oMockPayment = $this->getMock('oxPayment', array('fcpoGetUserPaymentId'));
        $oMockPayment->expects($this->any())->method('fcpoGetUserPaymentId')->will($this->returnValue('someUserPaymentId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->onConsecutiveCalls($oMockPayment, $oMockUserPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $oResponse = $oExpect = $this->invokeMethod($oTestObject, '_fcGetPaymentByPaymentType', array($oMockUser, $sMockPaymentType));

        $this->assertEquals($oExpect, $oResponse);
    }

    /**
     * Testing _fcGetPaymentByPaymentType for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcGetPaymentByPaymentType_Negative() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oMockUser = $this->getMock('oxUser', array('getId'));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $sMockPaymentType = null;

        $oMockDatabase = $this->getMock('oxDb', array('GetOne'));
        $oMockDatabase->expects($this->any())->method('GetOne')->will($this->returnValue('someValue'));

        $oMockUserPayment = $this->getMock('oxuserpayment', array('load'));
        $oMockUserPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockUserPayment));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        $this->invokeSetAttribute($oTestObject, '_oFcpoDb', $oMockDatabase);

        $this->assertEquals(false, $this->invokeMethod($oTestObject, '_fcGetPaymentByPaymentType', array($oMockUser, $sMockPaymentType)));
    }

    /**
     * Testing _assignDebitNoteParams for coverage
     * 
     * @param void
     * @return void
     */
    public function test__assignDebitNoteParams_Coverage() {
        $oMockUser = $this->getMock('oxUser', array('getId'));
        $oMockUser->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $oMockUserPayment = $this->getMock('oxuserpayment', array('load'));
        $oMockUserPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oMockPaymentData = new stdClass();
        $oMockPaymentData->name = 'someName';
        $oMockPaymentData->value = 'someValue';
        $aMockPaymentData = array($oMockPaymentData);

        $oMockUtils = $this->getMock('oxUtils', array('assignValuesFromText'));
        $oMockUtils->expects($this->any())->method('assignValuesFromText')->will($this->returnValue($aMockPaymentData));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', '_fcGetPaymentByPaymentType', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcGetPaymentByPaymentType')->will($this->returnValue($oMockUserPayment));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));


        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetUtils')->will($this->returnValue($oMockUtils));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(false, $this->invokeMethod($oTestObject, '_assignDebitNoteParams'));
    }

    /**
     * Testing getDynValue for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getDynValue_Coverage() {
        $aPaymentList = array();
        $aPaymentList['fcpodebitnote'] = 'someValue';

        $aDynValues = array('someDynValue');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfigParam', 'getPaymentList', '_assignDebitNoteParams'));
        $oTestObject->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getPaymentList')->will($this->returnValue($aPaymentList));
        $oTestObject->expects($this->any())->method('_assignDebitNoteParams')->will($this->returnValue(true));

        $this->invokeSetAttribute($oTestObject, '_aDynValue', $aDynValues);

        $this->assertEquals($aDynValues, $oTestObject->getDynValue());
    }

    /**
     * Testing fcGetBillCountry for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcGetBillCountry_Coverage() {
        $oMockCountry = $this->getMock('oxCountry', array('load'));
        $oMockCountry->expects($this->any())->method('load')->will($this->returnValue(true));
        $oMockCountry->oxcountry__oxisoalpha2 = new oxField('de');

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUserBillCountryId'));
        $oTestObject->expects($this->any())->method('getUserBillCountryId')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockCountry));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('de', $oTestObject->fcGetBillCountry());
    }

    /**
     * Testing _setValues for coverage
     * 
     * @param void
     * @return void
     */
    public function test__setValues_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcIsPayOnePaymentType', 'fcShowApprovalMessage', 'fcGetApprovalText'));
        $oTestObject->expects($this->any())->method('_fcIsPayOnePaymentType')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcShowApprovalMessage')->will($this->returnValue('someMessage'));
        $oTestObject->expects($this->any())->method('fcGetApprovalText')->will($this->returnValue('someText'));

        $aPaymentList = array();
        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcShowApprovalMessage', 'fcBoniCheckNeeded'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue(true));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $aPaymentList[] = $oMockPayment;

        $this->assertEquals(null, $oTestObject->_setValues($aPaymentList));
    }

    /**
     * Testing _fcGetCurrentVersion for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcGetCurrentVersion_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetIntShopVersion')->will($this->returnValue(4800));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(4800, $this->invokeMethod($oTestObject, '_fcGetCurrentVersion'));
    }

    /**
     * Testing _setDeprecatedValues for coverage
     * 
     * @param void
     * @return void
     */
    public function test__setDeprecatedValues_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
                '_fcGetCurrentVersion',
                '_fcIsPayOnePaymentType',
                'fcShowApprovalMessage',
                'fcBoniCheckNeeded',
                'fcGetApprovalText'
            )
        );
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4200));
        $oTestObject->expects($this->any())->method('_fcIsPayOnePaymentType')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcShowApprovalMessage')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('fcGetApprovalText')->will($this->returnValue('someText'));

        $oMockLang = $this->getMock('oxLang', array('getId'));
        $oMockLang->expects($this->any())->method('getId')->will($this->returnValue('someId'));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetLang')->will($this->returnValue($oMockLang));

        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $aMockPaymentList = array();
        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcBoniCheckNeeded'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));
        $oMockPayment->oxpayments__oxlongdesc = new oxField('someDesc');
        $aMockPaymentList[] = $oMockPayment;

        // $this->invokeMethod($oTestObject, '_setDeprecatedValues', array($aMockPaymentList)
        $this->assertEquals(null, $oTestObject->_setDeprecatedValues($aMockPaymentList));
    }

    /**
     * Testing _fcpoGetKlarnaLang for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcpoGetKlarnaLang_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('fcGetBillCountry'));
        $oTestObject->expects($this->any())->method('fcGetBillCountry')->will($this->returnValue('de'));

        $this->assertEquals('de_de', $this->invokeMethod($oTestObject, '_fcpoGetKlarnaLang'));
    }

    /**
     * Testing _fcIsPayOnePaymentType for coverage
     * 
     * @param void
     * @return void
     */
    public function test__fcIsPayOnePaymentType_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView');

        $this->assertEquals(true, $this->invokeMethod($oTestObject, '_fcIsPayOnePaymentType', array('fcpopayadvance')));
    }

    /**
     * Testing fcpoProcessValidation for error
     */
    public function tests__fcpoProcessValidation_Error() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
                '_fcpoSetKlarnaCampaigns',
                '_fcpoCheckBoniMoment',
                '_fcpoSetBoniErrorValues',
                '_fcpoSetMandateParams',
                '_fcCleanupSessionFragments'
            )
        );
        $oTestObject->expects($this->any())->method('_fcpoSetKlarnaCampaigns')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckBoniMoment')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoSetBoniErrorValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetMandateParams')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcCleanupSessionFragments')->will($this->returnValue('someText'));

        $oMockPayment = $this->getMock('oxPayment', array('load'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoProcessValidation('order', 'somePaymentId'));
    }

    /**
     * Testing fcpoProcessValidation for ok
     */
    public function tests__fcpoProcessValidation_Ok() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array(
                '_fcpoSetKlarnaCampaigns',
                '_fcpoCheckBoniMoment',
                '_fcpoSetBoniErrorValues',
                '_fcpoSetMandateParams',
                '_fcCleanupSessionFragments'
            )
        );
        $oTestObject->expects($this->any())->method('_fcpoSetKlarnaCampaigns')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckBoniMoment')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetBoniErrorValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetMandateParams')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcCleanupSessionFragments')->will($this->returnValue('someText'));

        $oMockPayment = $this->getMock('oxPayment', array('load'));
        $oMockPayment->expects($this->any())->method('load')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockPayment));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals('order', $oTestObject->_fcpoProcessValidation('order', 'somePaymentId'));
    }
    

    /**
     * Testing _fcpoPayolutionPreCheck with valid bankdata
     */
    public function test__fcpoPayolutionPreCheck_ValidBankData() {
       $oTestObject = $this->getMock('fcPayOnePaymentView', 
                array(
                    '_fcpoIsPayolution', 
                    '_fcpoPayolutionSaveRequestedValues',
                    '_fcpoCheckAgreed', 
                    '_fcpoGetPayolutionBankData', 
                    '_fcpoValidateBankData',
                    '_fcpoCheckSepaAgreed',
                    '_fcpoPerformPayolutionPreCheck',
                ));
        
        $oTestObject->expects($this->any())->method('_fcpoIsPayolution')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionSaveRequestedValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionBankData')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoValidateBankData')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckSepaAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoPerformPayolutionPreCheck')->will($this->returnValue(true));
        
        $this->assertEquals(null, $oTestObject->_fcpoPayolutionPreCheck(null, 'someId'));
    }

    /**
     * Testing _fcpoPayolutionPreCheck with invalid bankdata
     */
    public function test__fcpoPayolutionPreCheck_InvalidBankData() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', 
                array(
                    '_fcpoIsPayolution', 
                    '_fcpoPayolutionSaveRequestedValues',
                    '_fcpoCheckAgreed', 
                    '_fcpoGetPayolutionBankData', 
                    '_fcpoValidateBankData',
                    '_fcpoCheckSepaAgreed',
                    '_fcpoPerformPayolutionPreCheck',
                ));
        
        $oTestObject->expects($this->any())->method('_fcpoIsPayolution')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionSaveRequestedValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionBankData')->will($this->returnValue($aMockBankData));
        $oTestObject->expects($this->any())->method('_fcpoValidateBankData')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoCheckSepaAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoPerformPayolutionPreCheck')->will($this->returnValue(false));
        
        $this->assertEquals(null, $oTestObject->_fcpoPayolutionPreCheck(null, 'someId'));
    }
    
    /**
     * Testing _fcpoPayolutionPreCheck with sepa check
     */
    public function test__fcpoPayolutionPreCheck_Sepa() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', 
                array(
                    '_fcpoIsPayolution', 
                    '_fcpoPayolutionSaveRequestedValues',
                    '_fcpoCheckAgreed', 
                    '_fcpoGetPayolutionBankData', 
                    '_fcpoValidateBankData',
                    '_fcpoCheckSepaAgreed',
                    '_fcpoPerformPayolutionPreCheck',
                ));
        
        $oTestObject->expects($this->any())->method('_fcpoIsPayolution')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoPayolutionSaveRequestedValues')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionBankData')->will($this->returnValue($aMockBankData));
        $oTestObject->expects($this->any())->method('_fcpoValidateBankData')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoCheckSepaAgreed')->will($this->returnValue(false));
        $oTestObject->expects($this->any())->method('_fcpoPerformPayolutionPreCheck')->will($this->returnValue(false));
        
        $this->assertEquals(null, $oTestObject->_fcpoPayolutionPreCheck(null, 'someId'));
    }

    /**
     * Testing _fcpoValidateBankData for coverage
     */
    public function test__fcpoValidateBankData_Coverage() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );
        $oTestObject = oxNew('fcPayOnePaymentView');
        
        $this->assertEquals(true, $oTestObject->_fcpoValidateBankData($aMockBankData));
    }
    
    /**
     * Testing _fcpoGetPayolutionBankData for coverage
     */
    public function test__fcpoGetPayolutionBankData_Coverage() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );

        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockBankData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(false, $oTestObject->_fcpoGetPayolutionBankData());
    }
    
    /**
     * Testing _fcpoCheckAgreed for coverage
     */
    public function test__fcpoCheckAgreed_Coverage() {
        $aMockData = array(
            'fcpo_payolution_agreed' => 'agreed',
        );

        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(true, $oTestObject->_fcpoCheckAgreed());
    }
    
    /**
     * Testing _fcpoCheckSepaAgreed for coverage
     */
    public function test__fcpoCheckSepaAgreed_Coverage() {
        $aMockData = array(
            'fcpo_payolution_sepa_agreed' => 'agreed',
        );

        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(true, $oTestObject->_fcpoCheckSepaAgreed());
    }
    
    /**
     * Testing _fcpoPayolutionSaveRequestedValues for coverage
     */
    public function test__fcpoPayolutionSaveRequestedValues_Coverage() {
        $aMockData = array(
            'fcpo_payolution_birthdate_year' => '1978',
            'fcpo_payolution_birthdate_month' => '12',
            'fcpo_payolution_birthdate_day' => '07',
            'fcpo_payolution_ustid' => 'someUstid',
        );
        
        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));
        $oMockUser->oxuser__oxbirthdate = new oxField('1977-12-08', oxField::T_RAW);
        $oMockUser->oxuser__oxustid = new oxField('someOtherUstid', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue($aMockData));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(null, $oTestObject->_fcpoPayolutionSaveRequestedValues());        
    }
    
    /**
     * Testing _fcpoIsPayolution for checking valid response on given payolution id
     */
    public function test__fcpoIsPayolution_IsPayolutionDebit() {
        $sMockPaymentId = 'fcpopo_debitnote';
        $oTestObject = oxNew('fcPayOnePaymentView');
        
        $this->assertEquals(true, $oTestObject->_fcpoIsPayolution($sMockPaymentId));
    }
    
    /**
     * Testing _fcpoPerformPayolutionPreCheck for error case
     */
    public function test__fcpoPerformPayolutionPreCheck_Error() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );
        
        $aMockResponse = array('status'=>'ERROR','workorderid'=>'someId');
        
        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));
        $oMockUser->oxuser__oxbirthdate = new oxField('1977-12-08', oxField::T_RAW);
        $oMockUser->oxuser__oxustid = new oxField('someUstid', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser','_fcpoGetPayolutionBankData'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionBankData')->will($this->returnValue($aMockBankData));
        
        $oMockRequest = $this->getMock('fcporequest', array('sendRequestPayolutionPreCheck'));
        $oMockRequest->expects($this->any())->method('sendRequestPayolutionPreCheck')->will($this->returnValue($aMockResponse));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(false, $oTestObject->_fcpoPerformPayolutionPreCheck('someId'));
    }

    /**
     * Testing _fcpoPerformPayolutionPreCheck for valid case
     */
    public function test__fcpoPerformPayolutionPreCheck_OK() {
        $aMockBankData = array(
            'fcpo_payolution_accountholder' => 'Some Person',
            'fcpo_payolution_iban' => 'DE12500105170648489890',
            'fcpo_payolution_bic' => 'BELADEBEXXX',
        );
        
        $aMockResponse = array('status'=>'OK','workorderid'=>'someId');
        
        $oMockUser = $this->getMock('oxUser', array('save'));
        $oMockUser->expects($this->any())->method('save')->will($this->returnValue(true));
        $oMockUser->oxuser__oxbirthdate = new oxField('1977-12-08', oxField::T_RAW);
        $oMockUser->oxuser__oxustid = new oxField('someUstid', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser','_fcpoGetPayolutionBankData'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_fcpoGetPayolutionBankData')->will($this->returnValue($aMockBankData));
        
        $oMockRequest = $this->getMock('fcporequest', array('sendRequestPayolutionPreCheck'));
        $oMockRequest->expects($this->any())->method('sendRequestPayolutionPreCheck')->will($this->returnValue($aMockResponse));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $this->assertEquals(true, $oTestObject->_fcpoPerformPayolutionPreCheck('someId'));
    }

    /**
     * Testing fcpoSetMandateParams for coverage
     */
    public function test__fcpoSetMandateParams_Coverage() {
        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcpoGetOperationMode'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('fcpodebitnote'));
        $oMockPayment->expects($this->any())->method('fcpoGetOperationMode')->will($this->returnValue('test'));

        $oMockRequest = $this->getMock('fcporequest', array('sendRequestManagemandate'));
        $oMockRequest->expects($this->any())->method('sendRequestManagemandate')->will($this->returnValue(true));

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someParam'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoHandleMandateResponse', 'getConfig'));
        $oTestObject->expects($this->any())->method('_fcpoHandleMandateResponse')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('getFactoryObject')->will($this->returnValue($oMockRequest));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoSetMandateParams($oMockPayment));
    }

    /**
     * Testing _fcpoHandleMandateResponse for error case
     */
    public function test__fcpoHandleMandateResponse_Error() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aMockResponse['status'] = 'ERROR';

        $this->assertEquals(null, $oTestObject->_fcpoHandleMandateResponse($aMockResponse));
    }

    /**
     * Testing _fcpoHandleMandateResponse for ok case
     */
    public function test__fcpoHandleMandateResponse_Ok() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aMockResponse['status'] = 'OK';
        $aMockResponse['mandate_status'] = 'someMandateStatus';

        $this->assertEquals(null, $oTestObject->_fcpoHandleMandateResponse($aMockResponse));
    }

    /**
     * Testing _fcpoSetBoniErrorValues for coverage
     */
    public function test__fcpoSetBoniErrorValues_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someParam'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcGetCurrentVersion','fcGetLangId'));
        $oTestObject->expects($this->any())->method('_fcGetCurrentVersion')->will($this->returnValue(4800));
        $oTestObject->expects($this->any())->method('fcGetLangId')->will($this->returnValue(0));
        

        $oMockBasket = $this->getMock('oxBasket', array('setTsProductId'));
        $oMockBasket->expects($this->any())->method('setTsProductId')->will($this->returnValue($oMockBasket));

        $oMockSession = $this->getMock('oxSession', array('getBasket'));
        $oMockSession->expects($this->any())->method('getBasket')->will($this->returnValue($oMockBasket));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoDeleteSessionVariable')->will($this->returnValue(true));
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('fcpoGetSession')->will($this->returnValue($oMockSession));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoSetBoniErrorValues('someId'));
    }

    /**
     * Testing _fcpoCheckBoniMoment for coverage
     */
    public function test__fcpoCheckBoniMoment_Coverage() {
        $oMockPayment = oxNew('oxPayment');

        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('after'));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('_fcpoCheckAddressAndScore', 'getConfig'));
        $oTestObject->expects($this->any())->method('_fcpoCheckAddressAndScore')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));

        $this->assertEquals(true, $oTestObject->_fcpoCheckBoniMoment($oMockPayment));
    }

    /**
     * Testing _fcpoCheckAddressAndScore for case that check is needed
     */
    public function test__fcpoCheckAddressAndScore_CheckNeeded() {
        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcBoniCheckNeeded'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(true));

        $oMockUser = $this->getMock('oxUser', array('checkAddressAndScore', '_fcpoCheckUserBoni'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->expects($this->any())->method('_fcpoCheckUserBoni')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', '_fcpoValidateApproval', '_fcpoSetNotChecked'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_fcpoValidateApproval')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetNotChecked')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(array('someValue')));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoCheckAddressAndScore($oMockPayment));
    }

    /**
     * Testing _fcpoCheckAddressAndScore for case that check is needed
     */
    public function test__fcpoCheckAddressAndScore_CheckNotNeeded() {
        $oMockPayment = $this->getMock('oxPayment', array('getId', 'fcBoniCheckNeeded'));
        $oMockPayment->expects($this->any())->method('getId')->will($this->returnValue('someId'));
        $oMockPayment->expects($this->any())->method('fcBoniCheckNeeded')->will($this->returnValue(false));

        $oMockUser = $this->getMock('oxUser', array('checkAddressAndScore', '_fcpoCheckUserBoni'));
        $oMockUser->expects($this->any())->method('checkAddressAndScore')->will($this->returnValue(true));
        $oMockUser->expects($this->any())->method('_fcpoCheckUserBoni')->will($this->returnValue(true));

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser', '_fcpoValidateApproval', '_fcpoSetNotChecked'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('_fcpoValidateApproval')->will($this->returnValue(true));
        $oTestObject->expects($this->any())->method('_fcpoSetNotChecked')->will($this->returnValue(true));

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(array('someValue')));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(true, $oTestObject->_fcpoCheckAddressAndScore($oMockPayment));
    }

    /**
     * Testing _fcpoSetNotChecked for coverage
     */
    public function test__fcpoSetNotChecked_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoSetSessionVariable')->will($this->returnValue(true));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(null, $oTestObject->_fcpoSetNotChecked(true, false));
    }

    /**
     * Testing _fcpoCheckUserBoni for coverage
     */
    public function test__fcpoCheckUserBoni_Coverage() {
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxboni = new oxField(10);

        $oMockPayment = oxNew('oxPayment');
        $oMockPayment->oxpayments__oxfromboni = new oxField(20);

        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));

        $this->assertEquals(false, $oTestObject->_fcpoCheckUserBoni(true, $oMockPayment));
    }

    /**
     * Testing  _fcpoValidateApproval for coverage
     */
    public function test__fcpoValidateApproval_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aMockApproval = array('someId' => 'false');
        $sMockPaymentId = 'someId';

        $this->assertEquals(false, $oTestObject->_fcpoValidateApproval($sMockPaymentId, $aMockApproval));
    }

    /**
     * Testing _fcpoGetDynValues for coverage
     */
    public function test__fcpoGetDynValues_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');

        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetRequestParameter')->will($this->returnValue(false));
        $oHelper->expects($this->any())->method('fcpoGetSessionVariable')->will($this->returnValue(array('someValue')));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);

        $this->assertEquals(array('someValue'), $oTestObject->_fcpoGetDynValues());
    }
    
    /**
     * Testing fcpoShowB2B with activated B2B mode
     */
    public function test_fcpoShowB2B_B2BModeActive() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(true));
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxcompany = new oxField('someCompany', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        
        $this->assertEquals(true, $oTestObject->fcpoShowB2B());
    }

    /**
     * Testing fcpoShowB2B with deactivated B2B mode
     */
    public function test_fcpoShowB2B_B2BModeInActive() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxcompany = new oxField('someCompany', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        
        $this->assertEquals(false, $oTestObject->fcpoShowB2B());
    }
    
    /**
     * Testing fcpoShowB2C for coverage
     */
    public function test_fcpoShowB2C_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue(false));
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxbirthdate = new oxField('1978-12-07', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig', 'getUser', 'fcpoShowB2B'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        $oTestObject->expects($this->any())->method('fcpoShowB2B')->will($this->returnValue(false));
        
        $this->assertEquals(false, $oTestObject->fcpoShowB2C());
    }
    
    /**
     * Testing fcpoGetBirthdayField for coverage
     */
    public function test_fcpoGetBirthdayField_Coverage() {
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('fcpoGetUserValue'));
        $oTestObject->expects($this->any())->method('fcpoGetUserValue')->will($this->returnValue('1978-12-07'));
        
        $this->assertEquals('1978', $oTestObject->fcpoGetBirthdayField('year'));
    }
    
    /**
     * Testing fcpoGetUserValue coverage
     */
    public function test_fcpoGetUserValue_Coverage() {
        $oMockUser = oxNew('oxUser');
        $oMockUser->oxuser__oxbirthdate = new oxField('1978-12-07', oxField::T_RAW);
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getUser'));
        $oTestObject->expects($this->any())->method('getUser')->will($this->returnValue($oMockUser));
        
        $this->assertEquals('1978-12-07', $oTestObject->fcpoGetUserValue('oxbirthdate'));
    }
    
    /**
     * Testing fcpoGetPayolutionAgreementLink coverage
     */
    public function test_fcpoGetPayolutionAgreementLink_Coverage() {
        $oMockConfig = $this->getMock('oxConfig', array('getConfigParam'));
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('someCompany'));
        
        $sExpect = 'https://payment.payolution.com/payolution-payment/infoport/dataprivacydeclaration?mId='.  base64_encode('someCompany');
        
        $oTestObject = $this->getMock('fcPayOnePaymentView', array('getConfig'));
        $oTestObject->expects($this->any())->method('getConfig')->will($this->returnValue($oMockConfig));
        
        $this->assertEquals($sExpect, $oTestObject->fcpoGetPayolutionAgreementLink());
    }
    
    /**
     * 
     */
    public function test_fcpoGetPayolutionSepaAgreementLink_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $sExpect = 'https://payment.payolution.com/payolution-payment/infoport/sepa/mandate.pdf';
        $this->assertEquals($sExpect, $oTestObject->fcpoGetPayolutionSepaAgreementLink());
    }
    
    /**
     * Testing fcpoGetNumericRange for coverage
     */
    public function test__fcpoGetNumericRange_Coverage() {
        $aExpect = array('01','02','03');
        $oTestObject = oxNew('fcPayOnePaymentView');
        $this->assertEquals($aExpect, $oTestObject->_fcpoGetNumericRange(1,3,2));
    }
    
    /**
     * Testing fcpoGetYearRange for coverage
     */
    public function test_fcpoGetYearRange_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        // I will not prepare a hundred entries array ;-)
        $aExpect = $aRange = $oTestObject->fcpoGetYearRange();
        $this->assertEquals($aExpect, $aRange);
    }
    
    /**
     * Testing fcpoGetMonthRange for coverage
     */
    public function test_fcpoGetMonthRange_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aExpect = $aRange = $oTestObject->fcpoGetMonthRange();
        $this->assertEquals($aExpect, $aRange);
    }
    
    /**
     * Testing fcpoGetDayRange for coverage
     */
    public function test_fcpoGetDayRange_Coverage() {
        $oTestObject = oxNew('fcPayOnePaymentView');
        $aExpect = $aRange = $oTestObject->fcpoGetDayRange();
        $this->assertEquals($aExpect, $aRange);
    }
}
