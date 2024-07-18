import {
    LegacyCard,
    IndexTable,
    useBreakpoints,
    useIndexResourceState,
} from "@shopify/polaris";
import ShopifyProductVariantIndexTableRow from "./ShopifyProductVariantIndexTableRow.jsx";

const ShopifyProductVariantIndexTable = ({ variants }) => {
    const { selectedResources, allResourcesSelected, handleSelectionChange } =
        useIndexResourceState(variants);

    const resourceName = {
        singular: "Shopify Product Variant",
        plural: "Shopify Product Variants",
    };

    return (
        <LegacyCard>
            <IndexTable
                condensed={useBreakpoints().smDown}
                resourceName={resourceName}
                itemCount={variants.length}
                headings={[
                    { title: "Attributes" },
                    { title: "SKU" },
                    { title: "Stock" },
                    { title: "Price" },
                ]}
                selectable={false}
            >
                {variants.map((variant, index) => (
                    <ShopifyProductVariantIndexTableRow
                        key={index}
                        variant={variant}
                        index={index}
                    />
                ))}
            </IndexTable>
        </LegacyCard>
    );
};

export default ShopifyProductVariantIndexTable;
