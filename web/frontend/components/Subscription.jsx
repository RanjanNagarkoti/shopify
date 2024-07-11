import {Card, Page, Layout, TextContainer, Text, LegacyCard, Box, Stack, Divider} from "@shopify/polaris";
import {useAppQuery} from "../hooks";
import {useTranslation} from "react-i18next";
import {useState} from "react";

const Subscription = () => {
    const {t} = useTranslation();
    const [isLoading, setIsLoading] = useState(true);

    const {
        data,
        refetch: refetchSubscriptionDetails,
        isLoading: isLoadingSubscriptionDetails,
        isRefetching: isRefetchingSubscriptionDetails,
    } = useAppQuery({
        url: "/api/subscription-detail",
        reactQueryOptions: {
            onSuccess: () => {
                setIsLoading(false);
            },
        },
    });

    return (
        <LegacyCard sectioned>
            <Box paddingBlockEnd="2">
                <Stack
                    wrap={false}
                    spacing="extraTight"
                    distribution="trailing"
                    alignment="center"
                >
                    <Stack.Item fill>
                        <Text as="h4" variant="headingMd">
                            {t("SubscriptionCard.subscribedPlanHeading")}
                        </Text>
                    </Stack.Item>
                    <Stack.Item>
                        <Text variant="bodyMd" as="p" fontWeight="regular">
                            {isLoadingSubscriptionDetails ? "-" : data.data.charge_name}
                        </Text>
                    </Stack.Item>
                </Stack>
            </Box>
            <Box paddingBlockEnd="2">
                <Divider/>
            </Box>
            <Box>
                <Stack
                    wrap={false}
                    spacing="extraTight"
                    distribution="trailing"
                    alignment="center"
                >
                    <Stack.Item fill>
                        <Text as="h4" variant="headingMd">
                            {t("SubscriptionCard.subscribedChargeHeading")}
                        </Text>
                    </Stack.Item>
                    <Stack.Item>
                        <Text variant="bodyMd" as="p" fontWeight="regular">
                            {isLoadingSubscriptionDetails ? "-" : `$ ${data.data.amount} / Month`}
                        </Text>
                    </Stack.Item>
                </Stack>
            </Box>
        </LegacyCard>
    );
};

export default Subscription;
