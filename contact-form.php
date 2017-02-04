
<main id="contact-bg">
    <h1>Contact Form</h1>
	<p>We welcome your comments and questions.  Please fill out and submit the form below and I will get back to you as soon as possible.</p>
    <form method="post" action="submit.php">
        <fieldset id="contact-info">
            <legend>Your Contact Information <em>(All fields required)</em></legend>
            <label for="firstName">First Name:</label>
            <input type="text" name="firstName" id="firstName" required="required" placeholder="First Name"><br>
            <label for="lastName">Last Name:</label>
            <input type="text" name="lastName" id="lastName" required="required" placeholder="Last Name"><br>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required="required" placeholder="abc@xyc.com"><br>
        </fieldset>
        <fieldset>
            <legend>How Did You Find Us?</legend>
            <input type="checkbox" name="foundMe[]" value="Google" class="radio" id="google">
            <label for="google">Google</label>
            <br>
            <input type="checkbox" name="foundMe[]"  value="Facebook" class="checkbox" id="facebook">
            <label for="facebook">Facebook</label>
            <br>
            <input type="checkbox" name="foundMe[]"  value="Yahoo" class="checkbox" id="yahoo">
            <label for="yahoo">Yahoo</label>
            <br>
            <input type="checkbox" name="foundMe[]"  value="Twitter" class="checkbox" id="twitter">
            <label for="twitter">Twitter</label>
            <br>
            <input type="checkbox" name="foundMe[]" value="Friend" class="checkbox" id="friend">
            <label for="friend">From A Friend</label>
            <br>
            <input type="checkbox" name="foundMe[]" value="Other" class="checkbox" id="other">
            <label for="other">Other</label>
        </fieldset>
        <fieldset>
            <legend>Comments</legend>
            <textarea name="comments" id="comment" rows="5" cols="45"></textarea>
        </fieldset>
        <div class="formButtonSection">
            <input type="reset" class="formButton" value="Clear">
            <input type="submit" class="formButton" value="Submit">
        </div>
    </form>

    <br class="clear" />
</main>
