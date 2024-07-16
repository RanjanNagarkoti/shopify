import { Box, Pagination } from "@shopify/polaris";

const ShopifyProductIndexTablePagination = ({
    meta,
    refetchShopifyProduct,
    setPage,
}) => {
    return (
        <Box
            background="bg-app"
            padding="4"
            display="flex"
            justifyContent="center"
            alignItems="center"
        >
            <Pagination
                onPrevious={() => {
                    console.log("Previous");
                    setPage(meta.current_page - 1);
                    refetchShopifyProduct();
                }}
                onNext={() => {
                    console.log("Next");
                    setPage(meta.current_page + 1);
                    refetchShopifyProduct();
                }}
                type="table"
                hasNext={meta.current_page === meta.last_page ? false : true}
                hasPrevious={meta.current_page === 1 ? false : true}
                label={`${meta.from}-${meta.to} of ${meta.total} products`}
            />
        </Box>
    );
};

export default ShopifyProductIndexTablePagination;
