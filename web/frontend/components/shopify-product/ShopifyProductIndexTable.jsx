import {
    LegacyCard,
    IndexTable,
    useBreakpoints,
    useIndexResourceState,
} from "@shopify/polaris";
import ShopifyProductIndexTableRow from "./ShopifyProductIndexTableRow.jsx";

const ShopifyProductIndexTable = ({ products }) => {
    const { selectedResources, allResourcesSelected, handleSelectionChange } =
        useIndexResourceState(products);

    const resourceName = {
        singular: "Shopify Product",
        plural: "Shopify Products",
    };

    return (
        <LegacyCard>
            <IndexTable
                condensed={useBreakpoints().smDown}
                resourceName={resourceName}
                itemCount={products.length}
                headings={[
                    { title: "Product" },
                    { title: "Vendor" },
                    { title: "Product Type" },
                    { title: "Tags" },
                    { title: "View On" },
                    { title: "Status" },
                    { title: "Action" },
                ]}
                selectable={false}
            >
                {products.map((product, index) => (
                    <ShopifyProductIndexTableRow
                        key={index}
                        product={product}
                        index={index}
                    />
                ))}
            </IndexTable>
        </LegacyCard>
    );
};

export default ShopifyProductIndexTable;
