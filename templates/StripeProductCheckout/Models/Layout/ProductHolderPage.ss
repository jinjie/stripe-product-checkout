<div class="container">
    <div class="row">
        <% loop $Children %>
            <div class="col-6 col-md-3">
                <div class="card">
                    <img src="{$Images.Sort(SortOrder).First.Fill(300, 300).URL}" class="card-img-top" alt="">

                    <div class="card-body">
                        <p class="font-weight-bold">
                            <a href="{$Link}">{$Title}</a>
                        </p>

                        <p>Price: {$UnitPrice.Nice}</p>

                        {$BuyNowButton}
                    </div>
                </div>
            </div>
        <% end_loop %>
    </div>
</div>
