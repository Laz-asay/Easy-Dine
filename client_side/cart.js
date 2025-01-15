function addToCart(dishID, tableID) {
    if (!tableID) {
        alert("Table ID is not set. Please refresh the page.");
        return;
    }

    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const response = JSON.parse(this.responseText);
            if (response.success) {
                alert("Dish added to cart!");
            } else {
                alert(response.message || "Failed to add dish to cart.");
            }
        }
    };
    xhttp.open("POST", "add_to_cart.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("dish_id=" + encodeURIComponent(dishID) + "&table_id=" + encodeURIComponent(tableID));
}
