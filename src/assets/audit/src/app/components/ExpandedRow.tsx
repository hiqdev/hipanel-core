import React from "react";
import type { DescriptionsProps } from "antd";
import { Descriptions, Flex, Space, Typography } from "antd";
import { Differ, Viewer } from "json-diff-kit";
import "json-diff-kit/dist/viewer.css";
import type { DataType } from "../types";

const differ = new Differ({
  detectCircular: true,    // default `true`
  maxDepth: Infinity,      // default `Infinity`
  showModifications: true, // default `true`
  arrayDiffMethod: "lcs",  // default `"normal"`, but `"lcs"` may be more useful
});

const Desc = ({ text }: Readonly<{ text: string }>) => (
  <Flex justify="center" align="center" style={{ height: "100%" }}>
    <Typography.Title type="secondary" level={5} style={{ whiteSpace: "nowrap" }}>
      {text.toUpperCase()}
    </Typography.Title>
  </Flex>
);

export const ExpandedRow = ({ record }: { record: DataType }) => {
  const diff = differ.diff(record.diff.old, record.diff.new);
  const items: DescriptionsProps["items"] = Object.entries(record.request ?? {})
    .filter(([key]) => ["app", "log_id", "ip"].includes(key))
    .map(([key, value]) => ({
      key,
      label: key.split("_").map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(" ").toLocaleUpperCase(),
      children: value ?? "-",
    }));

  return (
    <Space direction={"vertical"} size={"large"}>
      <Descriptions size={"small"} bordered colon column={1} items={items} style={{ width: "50%" }} />
      <Flex gap={"medium"} style={{ width: "100%", height: "100%" }} justify={"space-around"} align={"center"} wrap={"wrap"}>
        <Desc text="Before" />
        <Desc text="After" />
      </Flex>
      <p style={{ margin: 0 }}>
        <Viewer
          diff={diff}
          indent={4}                 // default `2`
          lineNumbers={true}         // default `false`
          highlightInlineDiff={true} // default `false`
          inlineDiffOptions={{
            mode: "word",            // default `"char"`, but `"word"` may be more useful
            wordSeparator: " ",      // default `""`, but `" "` is more useful for sentences
          }}
        />
      </p>
    </Space>
  );
};
