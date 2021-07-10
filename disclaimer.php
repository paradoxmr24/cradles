<?php 
$role = 'All';
require 'includes/validate.php';
require_once 'includes/connection.php';
$title = 'Terms of Use';
$domain = explode('.',$_SERVER['SERVER_NAME']);
$domain = isset($domain[count($domain)-2]) ? $domain[count($domain)-2] . '.' . $domain[count($domain)-1] : $domain[count($domain)-1];
?>
<?php include 'includes/topbar.php'; ?>

<!----------------------Body------->
<section class="card rounded p-3 my-2 mx-2 border-left-primary">
<h1>Disclaimer for Cradles</h1>

<p>If you require any more information or have any questions about our site's disclaimer, please feel free to contact us by email at contact@<?php echo $domain ?></p>

<h2>Disclaimers for Cradles</h2>

<p>All the information on this website - <?php echo $domain ?> - is published in good faith and for general information purpose only. Cradles does not make any warranties about the completeness, reliability and accuracy of this information. Any action you take upon the information you find on this website (Cradles), is strictly at your own risk. Cradles will not be liable for any losses and/or damages in connection with the use of our website. Our Disclaimer was generated with the help of the <a href="https://www.disclaimergenerator.net/">Disclaimer Generator</a> and the <a href="https://www.disclaimer-generator.com">Disclaimer Generator</a>.</p>

<p>From our website, you can visit other websites by following hyperlinks to such external sites. While we strive to provide only quality links to useful and ethical websites, we have no control over the content and nature of these sites. These links to other websites do not imply a recommendation for all the content found on these sites. Site owners and content may change without notice and may occur before we have the opportunity to remove a link which may have gone 'bad'.</p>

<p>Please be also aware that when you leave our website, other sites may have different privacy policies and terms which are beyond our control. Please be sure to check the Privacy Policies of these sites as well as their "Terms of Service" before engaging in any business or uploading any information.</p>

<h2>Consent</h2>

<p>By using our website, you hereby consent to our disclaimer and agree to its terms.</p>

<h2>Update</h2>

<p>Should we update, amend or make any changes to this document, those changes will be prominently posted here.</p>
</section>
<?php include 'includes/bottom.php' ?>