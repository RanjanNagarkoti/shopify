import { Page, Layout, LegacyCard, Text, List, Button } from "@shopify/polaris";
import { useAuthenticatedFetch } from "../hooks";

const Pricing = () => {
    const fetch = useAuthenticatedFetch();

    const handleRedirection = async () => {
        const response = await fetch("/api/pricing-confirmation");

        if (response.ok) {
            const data = await response.json();
            const confirmation_url = data.confirmation_url;

            window.location.href = confirmation_url;
        } else {
            console.error(
                "Failed to fetch confirmation URL:",
                response.statusText,
            );
        }
    };

    return (
        <Page fullWidth>
            <Layout>
                <div className="w-4/12">
                    <Layout.Section variant="oneThird">
                        <LegacyCard title="Boldly Pursue Profits with Exponential Growth">
                            <LegacyCard.Section>
                                <Text variant="heading2xl">$ 5.0</Text>
                            </LegacyCard.Section>
                            <LegacyCard.Section title="Service We Provide">
                                <List type="bullet">
                                    <List.Item>
                                        Auto Sync Collections with Products
                                    </List.Item>
                                    <List.Item>
                                        Automated Inventory Management
                                    </List.Item>
                                    <List.Item>
                                        Real-time Sales Analytics
                                    </List.Item>
                                    <List.Item>
                                        Customizable Email Marketing Campaigns
                                    </List.Item>
                                    <List.Item>
                                        Personalized Product Recommendations
                                    </List.Item>
                                </List>
                                <div className="mt-4">
                                    <Button onClick={handleRedirection}>
                                        Get Started
                                    </Button>
                                </div>
                            </LegacyCard.Section>
                        </LegacyCard>
                    </Layout.Section>
                </div>
            </Layout>
        </Page>
    );
};

export default Pricing;
