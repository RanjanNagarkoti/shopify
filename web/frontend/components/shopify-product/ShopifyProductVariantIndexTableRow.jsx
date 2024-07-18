import {
    IndexTable,
} from "@shopify/polaris";

const ShopifyProductVariantIndexTableRow = ({ key, variant, index }) => {
    return (
        <IndexTable.Row id={variant.id} key={key} position={index}>
            <IndexTable.Cell>{variant.title}</IndexTable.Cell>
            <IndexTable.Cell>{variant.sku ? variant.sku : "N/A"}</IndexTable.Cell>
            <IndexTable.Cell>{variant.inventory_quantity}</IndexTable.Cell>
            <IndexTable.Cell>{variant.price}</IndexTable.Cell>
        </IndexTable.Row>
    );
};

export default ShopifyProductVariantIndexTableRow;
