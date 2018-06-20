# Magento2 Sample Request Module

This module extends the add to cart button with a "Request Sample" button. Clicking on it opens a modal with a custom form.
Customer can add his name, address and a message. 

#How to enable sample request:

<ol>
<li>Install module</li>
<li>Enable module under Stores > Configuration > Longneck Designs you find a setting to enable the module.</li>
<li>Find also a textarea field which will be added at the top of the sample request form</li>
<li>Enable product for sample request with clicking "yes" on the samplerequest attribute on product page in the backend.</li>
<li>Reindex and find all enabled products in the top select box of the sample request form</li>
</ol>

#Features

<ul>
<li>Adds "Request Sample" Button and Form to product detail page</li>
<li>Sample Request link in the footer links section</li>
<li>Dedicated samplerequest page under www.domain.com/samplerequest/</li>
</ul>

<h2>Composer Installation Instructions</h2>
TO DO...
<br />

After that, need to install this module as follows:
<pre>
  composer require magento/magento-composer-installer
  composer require longneck/samplerequest
</pre>

<br/>
<h2> Manual Installation Instructions</h2>
go to Magento2Project root dir 
create following Directory Structure :<br/>
<strong>/Magento2Project/app/code/Longneck/SampleRequest</strong>

<h3> Enable Sample Request</h3>
to Enable this module you need to follow these steps:

<strong>Enable the Module</strong>
<pre>bin/magento module:enable Longneck_SampleRequest</pre>

<strong>Run Upgrade Setup</strong>
<pre>bin/magento setup:upgrade</pre>
<strong>Re-Compile (in-case you have compilation enabled)</strong>
<pre>bin/magento setup:di:compile</pre>

