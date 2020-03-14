<div class="container">
    <div class="row">
        <div class="col-12 col-md-6">
            {$Images.Sort(SortOrder).First}
        </div>

        <div class="col-12 col-md-6">
            <h1>{$Title}</h1>

            {$Content}

            <p>{$UnitPrice.Nice}</p>

            {$CheckoutForm}
        </div>
    </div>
</div>
