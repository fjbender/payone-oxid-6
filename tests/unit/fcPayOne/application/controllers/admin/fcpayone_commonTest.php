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
 
class Unit_fcPayOne_Application_Controllers_Admin_fcpayone_common extends OxidTestCase {
    
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
        $method     = $reflection->getMethod($methodName);
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
        $property   = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }    
    

    /**
     * Testing fcpoGetVersion for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetVersion_Coverage() {
        $oTestObject    = oxNew('fcpayone_common');
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetModuleVersion')->will($this->returnValue('12.0'));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sExpect = '12.0';
        $this->assertEquals($sExpect, $oTestObject->fcpoGetVersion());
    }
    
    
    /**
     * Testing fcpoGetMerchantId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetMerchantId_Coverage() {
        $oTestObject    = oxNew('fcpayone_common');
        
        $oMockConfig = $this->getMockBuilder('oxConfig')->disableOriginalConstructor()->getMock();
        $oMockConfig->expects($this->any())->method('getConfigParam')->will($this->returnValue('12345'));
        
        $oHelper = $this->getMockBuilder('fcpohelper')->disableOriginalConstructor()->getMock();
        $oHelper->expects($this->any())->method('fcpoGetConfig')->will($this->returnValue($oMockConfig));
        $this->invokeSetAttribute($oTestObject, '_oFcpoHelper', $oHelper);
        
        $sExpect = '12345';
        $this->assertEquals($sExpect, $this->invokeMethod($oTestObject, 'fcpoGetMerchantId'));
    }
    
    
    /**
     * Testing fcpoGetIntegratorId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_fcpoGetIntegratorId_Coverage() {
        $oTestObject = $this->getMock('fcpayone_common', array('getIntegratorId'));
        $oTestObject->method('getIntegratorId')->will( $this->returnValue('someValue'));
        
        $this->assertEquals('someValue',$oTestObject->fcpoGetIntegratorId());
    }
    
    
    /**
     * Testing getIntegratorId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIntegratorId_Coverage_EE() {
        $oTestObject = $this->getMock('fcpayone_common', array('getShopEdition'));
        $oTestObject->method('getShopEdition')->will( $this->returnValue('EE'));
        
        $this->assertEquals('2029000',$this->invokeMethod($oTestObject, 'getIntegratorId'));
    }
    
    
    /**
     * Testing getIntegratorId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIntegratorId_Coverage_CE() {
        $oTestObject = $this->getMock('fcpayone_common', array('getShopEdition'));
        $oTestObject->method('getShopEdition')->will( $this->returnValue('CE'));
        
        $this->assertEquals('2027000',$this->invokeMethod($oTestObject, 'getIntegratorId'));
    }
    

    /**
     * Testing getIntegratorId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getIntegratorId_Coverage_PE() {
        $oTestObject = $this->getMock('fcpayone_common', array('getShopEdition'));
        $oTestObject->method('getShopEdition')->will( $this->returnValue('PE'));
        
        $this->assertEquals('2028000',$this->invokeMethod($oTestObject, 'getIntegratorId'));
    }
    

    /**
     * Testing getViewId for coverage
     * 
     * @param void
     * @return void
     */
    public function test_getViewId_Coverage() {
        $oTestObject    = oxNew('fcpayone_common');
        $this->assertEquals('dyn_fcpayone',$this->invokeMethod($oTestObject, 'getViewId'));
    }
}
