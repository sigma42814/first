<!-- resources/views/components/customer-search.blade.php -->
@props(['customers' => []]) <!-- Define the $customers prop -->

<div class="col-md-3">
    <label for="customerSearch" class="small">Customer Name</label>
    <input type="text" id="customerSearch" class="form-control form-control-sm" placeholder="Search customer..." onkeydown="handleCustomerSearch(event)">
</div>

<script>
    // Handle Enter key press for customer search
    function handleCustomerSearch(event) {
        if (event.key === 'Enter') {
            const query = event.target.value.trim();
            if (query) {
                // Redirect to the search page with the query
                window.location.href = `/customer-search?query=${query}`;
            }
        }
    }
</script>