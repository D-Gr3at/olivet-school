<?php
if (isset($_POST['submit'])) {
    var_dump($_POST);
}
?>

<form action="#" method="POST" id="form1">
    <input type="text" name="fullname">
    <input type="submit" value="Submit" name="submit">
</form>

<script>
    document.getElementById('#form1').addEventListener('submit', function(e) {
        e.preventDefault();
    })
</script>