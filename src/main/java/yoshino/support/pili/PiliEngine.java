package yoshino.support.pili;

import com.qiniu.pili.Client;
import com.qiniu.pili.Hub;
import yoshino.engine.StreamEngine;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by Volio on 2016/12/15.
 */
public class PiliEngine implements StreamEngine {

    private Client client;
    private Hub hub;
    private String hubName;
    private String RTMPPublishUrl;
    private String RTMPPlayUrl;
    private String HLSPlayUrl;
    private String HDLPlayUrl;
    private String SnapshotPlayUrl;

    @Override
    public String getPublishUrl(String streamKey) {
        return client.RTMPPublishURL(this.RTMPPublishUrl, this.hubName, streamKey, 1800);
    }

    @Override
    public Map<String, String> getPlayUrl(String streamKey) {
        Map<String, String> playUrlMap = new HashMap<>();
        playUrlMap.put("RTMP", client.RTMPPlayURL(this.RTMPPlayUrl, this.hubName, streamKey));
        playUrlMap.put("HLS", client.HLSPlayURL(this.HLSPlayUrl, this.hubName, streamKey));
        playUrlMap.put("HDL", client.HDLPlayURL(this.HDLPlayUrl, this.hubName, streamKey));
        return playUrlMap;
    }

    @Override
    public String getSnapshotPlayUrl(String streamKey) {
        return client.SnapshotPlayURL(this.SnapshotPlayUrl, this.hubName, streamKey);
    }

    public void setClient(Client client) {
        this.client = client;
    }

    public void setHub(Hub hub) {
        this.hub = hub;
    }

    public void setHubName(String hubName) {
        this.hubName = hubName;
    }

    public void setRTMPPublishUrl(String RTMPPublishUrl) {
        this.RTMPPublishUrl = RTMPPublishUrl;
    }

    public void setRTMPPlayUrl(String RTMPPlayUrl) {
        this.RTMPPlayUrl = RTMPPlayUrl;
    }

    public void setHLSPlayUrl(String HLSPlayUrl) {
        this.HLSPlayUrl = HLSPlayUrl;
    }

    public void setHDLPlayUrl(String HDLPlayUrl) {
        this.HDLPlayUrl = HDLPlayUrl;
    }

    public void setSnapshotPlayUrl(String snapshotPlayUrl) {
        SnapshotPlayUrl = snapshotPlayUrl;
    }
}
