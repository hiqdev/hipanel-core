import React from "react";
import { Tag, Typography } from "antd";
import { colors } from "../utils";

export const Restricted = () => <Typography.Text type={"danger"}>RESTRICTED</Typography.Text>;

export const OperationTag = ({ operation, count }: { operation: string; count?: number }) => (
  <Tag color={colors[operation] || "default"}>
    {operation.toUpperCase()}{count ? ` ×${count}` : ""}
  </Tag>
);
