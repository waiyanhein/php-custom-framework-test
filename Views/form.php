<div>
    <form method="GET">
        <input type="text" name="keyword" value="<?php echo (isset($_GET['keyword']))?htmlspecialchars($_GET['keyword']): '';  ?>" />
        <button type="submit">Search</button>
    </form>
</div>
