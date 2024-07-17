import { Page, Layout, Text, Spinner, LegacyCard } from "@shopify/polaris";
import { TitleBar } from "@shopify/app-bridge-react";
import { useTranslation } from "react-i18next";
import { useAppQuery } from "../hooks";
import { useState } from "react";
import ShopifyProductIndexTable from "../components/shopify-product/ShopifyProductIndexTable";
import ShopifyProductIndexTablePagination from "../components/shopify-product/ShopifyProductIndexTablePagination";

const ShopifyProduct = () => {
    const { t } = useTranslation();
    const [isLoading, setIsLoading] = useState(true);
    const [page, setPage] = useState(1);

    const {
        data,
        refetch: refetchShopifyProduct,
        isLoading: isLoadingShopifyProduct,
        isRefetching: isRefetchingShopifyProduct,
    } = useAppQuery({
        url: `/api/shopify-products?page=${page}`,
        reactQueryOptions: {
            onSuccess: (data) => {
                console.log("Fetched data:", data); // Log the data to inspect it
                setIsLoading(false);
            },
            onError: (error) => {
                console.error("Error fetching data:", error); // Log errors if any
            },
        },
    });

    return (
        <Page fullWidth>
            <TitleBar title={t("ShopifyProduct.title")} />
            <Layout>
                <Layout.Section>
                    {isLoading || isLoadingShopifyProduct ? (
                        <Text as="div" alignment="center">
                            <Spinner
                                accessibilityLabel="Spinner example"
                                size="large"
                            />
                        </Text>
                    ) : (
                        <LegacyCard>
                            <ShopifyProductIndexTable products={data.data} />
                            {data.data.length !== 0 && (
                                <ShopifyProductIndexTablePagination
                                    meta={data.meta}
                                    refetchShopifyProduct={
                                        refetchShopifyProduct
                                    }
                                    setPage={setPage}
                                />
                            )}
                        </LegacyCard>
                    )}
                </Layout.Section>
            </Layout>
        </Page>
    );
};

export default ShopifyProduct;
