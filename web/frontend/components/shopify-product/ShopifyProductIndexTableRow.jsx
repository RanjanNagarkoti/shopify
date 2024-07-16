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
import { ImageMajor, DeleteMajor } from "@shopify/polaris-icons";
import { shopifyImage } from "../../assets";
import capitalize from "../../hooks/capitalize.js";

const ShopifyProductIndexTableRow = ({ product, index }) => {
    return (
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
                    wrap={false}
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
                <Link url="youtube.com" external>
                    <Avatar source={shopifyImage} />
                </Link>
            </IndexTable.Cell>
            <IndexTable.Cell>
                <Badge tone="success">{capitalize(product.status)}</Badge>
            </IndexTable.Cell>
            <IndexTable.Cell>
                <Box>
                    <Button icon={DeleteMajor}>Remove</Button>
                </Box>
            </IndexTable.Cell>
        </IndexTable.Row>
    );
};

export default ShopifyProductIndexTableRow;
