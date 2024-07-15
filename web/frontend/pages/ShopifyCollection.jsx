import {
    Page,
    Layout,
    Text,
    IndexTable,
    LegacyCard,
    Spinner,
    Badge,
} from "@shopify/polaris";
import { TitleBar } from "@shopify/app-bridge-react";
import { useTranslation } from "react-i18next";
import { useAppQuery } from "../hooks";
import { useState } from "react";
import { useIndexResourceState } from "@shopify/polaris";
import capitalize from "../hooks/capitalize";

const ShopifyCollection = () => {
    const { t } = useTranslation();
    const [isLoading, setIsLoading] = useState(true);
    const [shopifyCollections, setshopifyCollections] = useState([]);

    const {
        data,
        refetch: refetchShopifyCollection,
        isLoading: isLoadingShopifyCollection,
        isRefetching: isRefetchingShopifyCollection,
    } = useAppQuery({
        url: "/api/shopify-collections",
        reactQueryOptions: {
            onSuccess: (res) => {
                setIsLoading(false);
            },
        },
    });

    const { selectedResources, allResourcesSelected, handleSelectionChange } =
        useIndexResourceState(shopifyCollections);

    const resourceName = {
        singular: "Shopify Collection",
        plural: "Shopify Collections",
    };

    if (isLoading || isLoadingShopifyCollection) {
        return <Spinner accessibilityLabel="Spinner example" size="large" />;
    }

    const rowMarkup = data.data.map(
        ({ id, title, type }, index) => (
            <IndexTable.Row
                id={id}
                key={id}
                selected={selectedResources.includes(id)}
                position={index}
            >
                <IndexTable.Cell>{id}</IndexTable.Cell>
                <IndexTable.Cell>{title}</IndexTable.Cell>
                <IndexTable.Cell>
                    <Badge tone="attention">{capitalize(type)}</Badge>
                </IndexTable.Cell>
            </IndexTable.Row>
        ),
    );

    return (
        <Page fullWidth>
            <TitleBar
                title={t("ShopifyCollection.title")}
                primaryAction={{
                    content: t("ShopifyCollection.primaryAction"),
                    onAction: () => console.log("Primary action"),
                }}
                secondaryActions={[
                    {
                        content: t("ShopifyCollection.secondaryAction"),
                        onAction: () => console.log("Secondary action"),
                    },
                ]}
            />
            <Layout>
                <Layout.Section>
                    <LegacyCard>
                        <IndexTable
                            resourceName={resourceName}
                            itemCount={data.data.length}
                            selectedItemsCount={
                                allResourcesSelected
                                    ? "All"
                                    : selectedResources.length
                            }
                            onSelectionChange={handleSelectionChange}
                            headings={[
                                { title: "ID" },
                                { title: "Title" },
                                { title: "Type",},
                            ]}
                            pagination={{
                                hasNext: true,
                                onNext: () => {},
                            }}
                        >
                            {rowMarkup}
                        </IndexTable>
                    </LegacyCard>
                </Layout.Section>
            </Layout>
        </Page>
    );
};

export default ShopifyCollection;
