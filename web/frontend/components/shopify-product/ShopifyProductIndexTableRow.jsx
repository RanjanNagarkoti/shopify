import {
    IndexTable,
    Stack,
    Thumbnail,
    Link,
    Tag,
    Badge,
    Button,
    Box,
    Avatar,
    Text,
} from "@shopify/polaris";
import { ImageMajor, ViewMinor } from "@shopify/polaris-icons";
import { shopifyImage } from "../../assets";
import capitalize from "../../hooks/capitalize.js";
import { useState, useCallback } from "react";
import ShopifyProductVariantIndexTable from "./ShopifyProductVariantIndexTable.jsx";

const ShopifyProductIndexTableRow = ({ product, index }) => {
    const [open, setOpen] = useState(false);

    const handleToggle = useCallback(() => setOpen((open) => !open), []);
    return (
        <>
            <IndexTable.Row id={product.id} key={product.id} position={index}>
                <IndexTable.Cell>
                    <Stack wrap={false} spacing="loose" alignment="center">
                        <Thumbnail
                            source={
                                product.image_src !== null
                                    ? product.image_src
                                    : ImageMajor
                            }
                            size="small"
                            alt="test"
                        />
                        <Text variant="bodyMd" fontWeight="normal" as="span">
                            {product.title}
                        </Text>
                    </Stack>
                </IndexTable.Cell>
                <IndexTable.Cell>{product.vendor}</IndexTable.Cell>
                <IndexTable.Cell>
                    {product.product_type ? product.product_type : "N/A"}
                </IndexTable.Cell>
                <IndexTable.Cell>
                    <Stack
                        wrap={true}
                        spacing="extraTight"
                        distribution="fill"
                        alignment="center"
                    >
                        {product.tags ? (
                            product.tags.map((tag, index) => (
                                <Tag key={index}>{tag}</Tag>
                            ))
                        ) : (
                            <Tag>N/A</Tag>
                        )}
                    </Stack>
                </IndexTable.Cell>
                <IndexTable.Cell>
                    <Link url={product.shopify_url} external>
                        <Avatar source={shopifyImage} />
                    </Link>
                </IndexTable.Cell>
                <IndexTable.Cell>
                    <Badge tone="success">{capitalize(product.status)}</Badge>
                </IndexTable.Cell>
                <IndexTable.Cell>
                    <Box>
                        <Button icon={ViewMinor} onClick={handleToggle}>
                            View Variants
                        </Button>
                    </Box>
                </IndexTable.Cell>
            </IndexTable.Row>
            {open ? (
                <tr>
                    <td
                        colSpan={9}
                        style={{ padding: "10px", backgroundColor: "#F1F8F5" }}
                    >
                        <ShopifyProductVariantIndexTable
                            variants={product.variants}
                        />
                    </td>
                </tr>
            ) : null}
        </>
    );
};

export default ShopifyProductIndexTableRow;
