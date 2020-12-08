
	<p class="lead">The core idea is to try and stick to the 7 standard REST/CRUD actions in your controllers:</p>

	<ul>
		<li>Index</li>
		<li>Show</li>
		<li>Create</li>
		<li>Store</li>
		<li>Edit</li>
		<li>Update</li>
		<li>Destroy</li>
	</ul>

	<p>Using this convention as a "rule" is a good way to force yourself to keep your controllers from becoming bloated, and can often lead to learning interesting new things about your domain.</p>

	<ol>
		<li>Give nested resources a dedicated controller</li>
		<li>Treat properties edited independently as separate resources</li>
		<li>Treat pivot models as their own resource</li>
		<li>Think of different states as different resources</li>
	</ol>

	<!--
		Tip 1: Nested Controller? New Controller.
		Tip 2: Edited independently: New Controller => (Updating profile image separately).
		Tip 3: Touches pivot records? New controller and probably a new model => Subscription/Unsubscription
		Tip 4: Transitions state? New Controller => Publish/UnPublish

		DeletedPostsController
		SendProposalController
		EmailProposalController
	-->
