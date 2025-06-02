<style>
.status_space{
    padding: 5px 15px;
    border-radius: 30px;
}
.status_pending{
    background-color: rgb(208, 178, 43);
    color:white;
}
.status_cancelled{
    background-color: rgb(209, 12, 12);
    color:white;
}
.status_paid{
    background-color: rgb(95, 193, 129);
    color:white;
}
.status_process{
    background-color: rgb(0, 255, 145);
    color:white;
}
.status_sending{
    background-color: rgb(0, 102, 255);
    color:white;
}
.status_completed{
    background-color: rgb(9, 126, 26);
    color:white;
}
</style>
<p class="status_space {{ \App\Models\Transaction::get_status_class($getState()) }}">{{ \App\Models\Transaction::get_status($getState()) }}</p>