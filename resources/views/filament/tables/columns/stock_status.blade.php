<style>
.available_stock{
    color: green;
    border: green 2px solid;
    border-radius: 10px;
    padding: 5px;
}

.unavailable_stock{
    color: red;
    border: red 2px solid;
    border-radius: 10px;
    padding: 5px;
}
</style>
@if ($getState() == true)
<p class="available_stock">Ada</p>
@else
<i class="fa fa-times"></i>
<p class="unavailable_stock">Kosong</p>
@endif