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
    TextContainer,
} from "@shopify/polaris";
import { ImageMajor, ViewMinor } from "@shopify/polaris-icons";
import { shopifyImage } from "../../assets";
import capitalize from "../../hooks/capitalize.js";
import { useState, useCallback } from "react";
import ShopifyProductVariantIndexTable from "./ShopifyProductVariantIndexTable.jsx";

const ShopifyProductIndexTableRow = ({ product, index }) => {
    const [open, setOpen] = useState(false);

    const handleToggle = useCallback(() => setOpen((open) => !open), []);

    function truncate(text, maxLength = 10) {
        if (text.length <= maxLength) {
            return text;
        } else {
            return text.substring(0, maxLength) + "...";
        }
    }

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
                <IndexTable.Cell>{product.inventory_count}</IndexTable.Cell>
                <IndexTable.Cell>
                    {product.sku ? truncate(product.sku, 15) : "N/A"}
                </IndexTable.Cell>
                <IndexTable.Cell>
                    {product.tags &&
                    product.tags.filter((tag) => tag.trim() !== "").length >
                        0 ? (
                        <Stack
                            wrap={true}
                            spacing="extraTight"
                            distribution="fill"
                            alignment="center"
                        >
                            {product.tags
                                .filter((tag) => tag.trim() !== "") // Filter out empty strings
                                .slice(0, 5)
                                .map((tag, index) => (
                                    <Tag key={index}>{tag}</Tag>
                                ))}
                        </Stack>
                    ) : (
                        <Tag>N/A</Tag>
                    )}
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
